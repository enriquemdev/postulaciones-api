<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $table = 'applications';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function applicationStatus(): BelongsTo {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id');
    }
}
