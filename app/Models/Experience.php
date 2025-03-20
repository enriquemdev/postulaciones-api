<?php

namespace App\Models;

use App\Traits\SerializesDatetimes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use SerializesDatetimes;

    protected $table = 'experiences';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current_job' => 'boolean',
    ];


    // Accesors for date only fields

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->translatedFormat('j \\d\\e F \\d\\e Y') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->translatedFormat('j \\d\\e F \\d\\e Y') : null;
    }


    // Relations
    public function applications(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
