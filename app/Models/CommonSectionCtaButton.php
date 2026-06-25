<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommonSectionCtaButton extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'common_section_id',
        'button_name',
        'page_id',
        'order',
    ];

    public function commonSection(): BelongsTo
    {
        return $this->belongsTo(PageCommonSection::class, 'common_section_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
