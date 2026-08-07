<?php

namespace App\Traits;

use App\Models\cpar_histories;

trait CparHistoryTrait
{
    public function addCparHistory(array $data)
    {
        return cpar_histories::create(array_merge([
            'cpar_id'        => null,
            'action'         => null,
            'old_status'     => null,
            'new_status'     => null,
            'reported_by'    => null,
            'assigned_id'  => null,
            'remarks'        => null,
        ], $data));
    }
}
