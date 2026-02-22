<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }
        $plans = Plan::all();
        return view('plan.index', compact('plans'));
    }

    public function create()
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }
        return view('plan.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'max_employees' => 'required|integer|min:1',
            'storage_limit' => 'required|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        Plan::create($request->all());
        return redirect()->route('plan.index')->with('success', 'Plan created successfully');
    }

    public function edit(string $id)
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }
        $plan = Plan::findOrFail($id);
        return view('plan.edit', compact('plan'));
    }

    public function update(Request $request, string $id)
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'max_employees' => 'required|integer|min:1',
            'storage_limit' => 'required|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update($request->all());
        return redirect()->route('plan.index')->with('success', 'Plan updated successfully');
    }

    public function destroy(string $id)
    {
        if (!Auth::user()->isAbleTo('manage-plan')) {
            abort(403, 'Unauthorized');
        }
        Plan::findOrFail($id)->delete();
        return redirect()->route('plan.index')->with('success', 'Plan deleted successfully');
    }
}
