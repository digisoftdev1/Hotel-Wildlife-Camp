<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RoomGallery extends Model
{
    protected $fillable = ['room_id', 'photos'];

    protected $casts = [
        'photos' => 'array',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function getPhotosCountAttribute(): int
    {
        return is_array($this->photos) ? count($this->photos) : 0;
    }


    public function hasPhotos(): bool
    {
        return !empty($this->photos) && count($this->photos) > 0;
    }

    public function addPhoto(string $photoPath): void
    {
        $photos = $this->photos ?? [];
        $photos[] = $photoPath;
        $this->photos = $photos;
        $this->save();
    }

    public function removePhoto(int $index): bool
    {
        $photos = $this->photos ?? [];

        if (!isset($photos[$index])) {
            return false;
        }

        $photoPath = $photos[$index];
        Storage::disk('public')->delete($photoPath);

        unset($photos[$index]);
        $this->photos = array_values($photos);
        $this->save();

        return true;
    }


    public function deleteAllPhotos(): void
    {
        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($gallery) {
            $gallery->deleteAllPhotos();
        });
    }
}