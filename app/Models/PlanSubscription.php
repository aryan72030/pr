<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'amount', 'duration', 'start_date', 'end_date', 'payment_method', 'transaction_id', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
