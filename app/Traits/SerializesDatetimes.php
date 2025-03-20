<?php

namespace App\Traits;

use Carbon\Carbon;

trait SerializesDatetimes
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::parse($date)->translatedFormat('j \\d\\e F \\d\\e Y H:i:s');
    }
}