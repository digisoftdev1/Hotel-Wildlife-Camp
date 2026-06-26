<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    public function listDocuments(?int $categoryId = null, ?string $search = null): LengthAwarePaginator
    {
        return Document::with('category', 'uploader')
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search, function ($query, $value) {
                $query->where(function ($subQuery) use ($value) {
                    $subQuery->where('title', 'like', '%' . $value . '%')
                        ->orWhere('title_np', 'like', '%' . $value . '%')
                        ->orWhere('description', 'like', '%' . $value . '%')
                        ->orWhere('description_np', 'like', '%' . $value . '%');
                });
            })
            ->latest()
            ->paginate(10);
    }

    public function categories(): Collection
    {
        return DocumentCategory::get(['title', 'id']);
    }

    public function createDocument(array $validated, UploadedFile $file, int $uploadedBy): Document
    {
        return DB::transaction(function () use ($validated, $file, $uploadedBy) {
            $filename = $this->uniqueTitleBasedFilename($validated['title'], $file);
            $path = $file->storeAs('documents', $filename, 'public');

            return Document::create([
                'title' => $validated['title'],
                'title_np' => $validated['title_np'] ?? null,
                'description' => $validated['description'] ?? null,
                'description_np' => $validated['description_np'] ?? null,
                'category_id' => $validated['category_id'],
                'file_path' => $path,
                'file_name' => basename($path),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => $uploadedBy,
            ]);
        });
    }

    public function updateDocument(Document $document, array $validated, ?UploadedFile $file = null): Document
    {
        return DB::transaction(function () use ($document, $validated, $file) {
            $data = [
                'title' => $validated['title'],
                'title_np' => $validated['title_np'] ?? null,
                'description' => $validated['description'] ?? null,
                'description_np' => $validated['description_np'] ?? null,
                'category_id' => $validated['category_id'],
            ];

            if ($file) {
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                $filename = $this->uniqueTitleBasedFilename($validated['title'], $file);
                $path = $file->storeAs('documents', $filename, 'public');
                $data['file_path'] = $path;
                $data['file_name'] = basename($path);
                $data['file_size'] = $file->getSize();
                $data['mime_type'] = $file->getMimeType();
            }

            $document->update($data);

            return $document->refresh();
        });
    }

    public function deleteDocument(Document $document): void
    {
        DB::transaction(function () use ($document) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();
        });
    }

    private function uniqueTitleBasedFilename(string $title, UploadedFile $file): string
    {
        $baseName = Str::slug($title);
        if ($baseName === '') {
            $baseName = 'document';
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
        $filename = $baseName . '.' . $extension;
        $counter = 1;

        while (Storage::disk('public')->exists('documents/' . $filename)) {
            $filename = $baseName . '-' . $counter++ . '.' . $extension;
        }

        return $filename;
    }
}