<?php

namespace App\Models;

use App\Traits\SerializesDatetimes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentType extends Model
{
    use SerializesDatetimes;

    protected $table = 'employment_types';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function applications(): HasMany {
        return $this->hasMany(Application::class, 'employment_type_id');
    }
}
