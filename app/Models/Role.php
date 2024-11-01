<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // 1 Role Bisa dimiliki lebih dari 1 / banyak User
    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
