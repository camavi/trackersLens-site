<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'message', 'source', 'locale', 'ip_address', 'user_agent', 'handled_at'])]
class ContactMessage extends Model
{
    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }
}
