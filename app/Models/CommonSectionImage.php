<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommonSectionImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'common_section_id',
        'image_path',
        'alt_text',
        'order',
    ];

    public function commonSection(): BelongsTo
    {
        return $this->belongsTo(PageCommonSection::class, 'common_section_id');
    }
}
