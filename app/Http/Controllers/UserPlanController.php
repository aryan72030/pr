<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        $currentPlan = Auth::user()->plan;
        return view('user-plan.index', compact('plans', 'currentPlan'));
    }

    public function subscribe(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();
        
        $user->update(['plan_id' => $plan->id]);
        
        return redirect()->route('user.plans')->with('success', 'Plan subscribed successfully');
    }
}
