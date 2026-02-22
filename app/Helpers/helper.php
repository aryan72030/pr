<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

function getValue()
{
    return Setting::where('create_id', createid())->get();
}


function email_config()
{
    $setting = Setting::where('create_id', createid())->pluck('value','key');
    try {
        Config::set('mail.default', $setting['mail_mailer']);
        Config::set('mail.mailers.smtp.host', $setting['mail_host']);
        Config::set('mail.mailers.smtp.port', $setting['mail_port']);
        Config::set('mail.mailers.smtp.username', $setting['mail_username']);
        Config::set('mail.mailers.smtp.password', $setting['mail_password']);
        Config::set('mail.mailers.smtp.encryption', $setting['mail_encryption']);
        Config::set('mail.mailers.from.address', $setting['mail_address']);
        Config::set('mail.mailers.from.name', $setting['mail_name']);
    } catch (Exception $e) {
        Log::error("Message: " . $e->getMessage());
    }
}

function createid()
{
    if (Auth::user()->hasRole('admin')) {
        return Auth::user()->id;
    } else {
        $id = Auth::user()->create_id;
        return $id;
    }
}

function stripe_config()
{
    $setting = Setting::where('create_id', createid())->pluck('value', 'key');
    try {
        Config::set('services.stripe.key', $setting['stripe_key'] ?? '');
        Config::set('services.stripe.secret', $setting['stripe_secret'] ?? '');
        Config::set('services.stripe.webhook.secret', $setting['stripe_webhook_secret'] ?? '');
        Config::set('cashier.currency', $setting['stripe_currency'] ?? 'USD');
    } catch (Exception $e) {
        Log::error("Stripe Config Error: " . $e->getMessage());
    }
}

function canAddEmployee()
{
    $user = Auth::user();
    if (!$user->plan) return false;
    
    $employeeCount = User::where('create_id', $user->id)->count();
    return $employeeCount < $user->plan->max_employees;
}

function getRemainingEmployeeSlots()
{
    $user = Auth::user();
    if (!$user->plan) return 0;
    
    $employeeCount = User::where('create_id', $user->id)->count();
    return max(0, $user->plan->max_employees - $employeeCount);
}
