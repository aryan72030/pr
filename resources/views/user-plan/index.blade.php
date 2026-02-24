@extends('masterpage.layout')

@section('title')
    {{ __('Choose Your Plan') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Choose Your Plan') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Plans') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <ul class="nav nav-pills justify-content-center" id="plan-duration-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $duration == 'monthly' ? 'active' : '' }}" href="?duration=monthly">{{ __('Monthly') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $duration == 'quarterly' ? 'active' : '' }}" href="?duration=quarterly">{{ __('Quarterly') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $duration == 'half_yearly' ? 'active' : '' }}" href="?duration=half_yearly">{{ __('Half Yearly') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $duration == 'yearly' ? 'active' : '' }}" href="?duration=yearly">{{ __('Yearly') }}</a>
                    </li>
                </ul>
            </div>

            @if($currentPlan)
                <div class="alert alert-info">
                    <strong>Current Plan:</strong> {{ $currentPlan->name }} - {{ $currentPlan->max_employees }} Employees - {{ $currentPlan->max_services }} Services
                    @if(Auth::user()->plan_expiry_date)
                        <br><strong>Expires on:</strong> {{ Auth::user()->plan_expiry_date->format('d M Y') }}
                    @endif
                </div>
            @endif

            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-md-4 mb-4">
                        <div class="card {{ $currentPlan && $currentPlan->id == $plan->id ? 'border-primary' : '' }}">
                            <div class="card-header text-center">
                                <h4>{{ $plan->name }}</h4>
                                @if($plan->type == 'free')
                                    <span class="badge bg-success">Free</span>
                                @else
                                    <span class="badge bg-primary">Paid</span>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if($plan->type == 'paid')
                                    <h2 class="mb-3">${{ number_format($plan->amount, 2) }}</h2>
                                @else
                                    <h2 class="mb-3">Free</h2>
                                @endif
                                <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $plan->duration)) }}</p>
                                <hr>
                                @if($plan->description)
                                    <p>{{ $plan->description }}</p>
                                @endif
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> {{ $plan->max_employees }} Employees</li>
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> {{ $plan->max_services }} Services</li>
                                </ul>
                                @if($currentPlan && $currentPlan->id == $plan->id)
                                    <button class="btn btn-success" disabled>Current Plan</button>
                                @else
                                    <form action="{{ route('user.plan.subscribe', $plan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Subscribe</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('user.plan.history') }}" class="btn btn-secondary">View Subscription History</a>
            </div>
        </div>
    </section>
@endsection
