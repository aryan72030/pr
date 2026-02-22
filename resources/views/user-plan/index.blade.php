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

            @if($currentPlan)
                <div class="alert alert-info">
                    <strong>Current Plan:</strong> {{ $currentPlan->name }} - {{ $currentPlan->max_employees }} Employees - {{ $currentPlan->storage_limit }}
                </div>
            @endif

            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-md-4 mb-4">
                        <div class="card {{ $currentPlan && $currentPlan->id == $plan->id ? 'border-primary' : '' }}">
                            <div class="card-header text-center">
                                <h4>{{ $plan->name }}</h4>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="mb-3">${{ number_format($plan->price_monthly, 2) }}<small>/month</small></h2>
                                <h5 class="text-muted">${{ number_format($plan->price_yearly, 2) }}/year</h5>
                                <hr>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> {{ $plan->max_employees }} Employees</li>
                                    <li class="mb-2"><i class="ti ti-check text-success"></i> {{ $plan->storage_limit }} Storage</li>
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
        </div>
    </section>
@endsection
