<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'max_employees', 'storage_limit', 'price_monthly', 'price_yearly', 'is_active'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
