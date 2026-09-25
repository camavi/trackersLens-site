<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['email', 'source', 'locale', 'ip_address', 'user_agent', 'notified_at'])]
class LaunchSubscription extends Model
{
    protected function casts(): array
    {
        return [
            'notified_at' => 'datetime',
        ];
    }
}
