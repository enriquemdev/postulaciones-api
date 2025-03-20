<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationStatus extends Model
{
    protected $table = 'application_statuses';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function applications(): HasMany {
        return $this->hasMany(Application::class, 'application_status_id');
    }
}
