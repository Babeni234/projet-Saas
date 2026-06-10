<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasCustomUuid;

class CompanyLegalDocument extends Model
{
    use HasCustomUuid;
    protected $fillable = [
        'company_profile_id',
        'document_type',
        'disk',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'ai_status',
        'ai_extracted_data',
        'ai_processed_at',
    ];

    protected function casts(): array
    {
        return [
            'ai_extracted_data' => 'array',
            'ai_processed_at' => 'datetime',
        ];
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }
}
