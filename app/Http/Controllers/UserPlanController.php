<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        
        if ($plan->type == 'free') {
            $this->createSubscription($user, $plan, null, 'free');
            return redirect()->route('user.plans')->with('success', 'Free plan subscribed successfully');
        }
        
        stripe_config();
        $stripeKey = config('services.stripe.key');
        
        return view('user-plan.payment', compact('plan', 'stripeKey'));
    }

    public function processPayment(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();
        
        stripe_config();
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            $charge = \Stripe\Charge::create([
                'amount' => $plan->amount * 100,
                'currency' => config('cashier.currency', 'usd'),
                'source' => $request->stripeToken,
                'description' => 'Plan Subscription: ' . $plan->name,
            ]);
            
            $this->createSubscription($user, $plan, $charge->id, 'stripe');
            
            return redirect()->route('user.plans')->with('success', 'Plan subscribed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function createSubscription($user, $plan, $transactionId, $paymentMethod)
    {
        $startDate = Carbon::now();
        $endDate = match($plan->duration) {
            'monthly' => $startDate->copy()->addDays(23),
            'quarterly' => $startDate->copy()->addMonthsNoOverflow(3),
            'half_yearly' => $startDate->copy()->addMonthsNoOverflow(6),
            'yearly' => $startDate->copy()->addYearNoOverflow(),
        };
        
        PlanSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $plan->amount ?? 0,
            'duration' => $plan->duration,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'status' => 'active',
        ]);
        
        $user->update([
            'plan_id' => $plan->id,
            'plan_expiry_date' => $endDate,
        ]);
    }

    public function history()
    {
        $subscriptions = Auth::user()->subscriptions()->with('plan')->latest()->get();
        return view('user-plan.history', compact('subscriptions'));
    }

    public function invoice($subscriptionId)
    {
        $subscription = PlanSubscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->with('plan')
            ->firstOrFail();
        
        return view('user-plan.invoice', compact('subscription'));
    }
}
