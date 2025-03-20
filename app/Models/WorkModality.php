<?php

namespace App\Models;

use App\Traits\SerializesDatetimes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkModality extends Model
{
    use SerializesDatetimes;

    protected $table = 'work_modalities';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function applications(): HasMany {
        return $this->hasMany(Application::class, 'work_modality_id');
    }
}
