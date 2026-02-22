@extends('masterpage.layout')

@section('title')
    {{ __('Edit Plan') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Edit Plan') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('plan.index') }}">{{ __('Plans') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Edit') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Plan Details') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('plan.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Plan Name') }}</label>
                                        <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Max Employees') }}</label>
                                        <input type="number" name="max_employees" class="form-control" value="{{ $plan->max_employees }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Storage Limit') }}</label>
                                        <input type="text" name="storage_limit" class="form-control" value="{{ $plan->storage_limit }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Price Monthly') }}</label>
                                        <input type="number" step="0.01" name="price_monthly" class="form-control" value="{{ $plan->price_monthly }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Price Yearly') }}</label>
                                        <input type="number" step="0.01" name="price_yearly" class="form-control" value="{{ $plan->price_yearly }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Status') }}</label>
                                        <div>
                                            <input type="radio" name="is_active" value="1" id="active" {{ $plan->is_active ? 'checked' : '' }}>
                                            <label for="active">Active</label>
                                            <input type="radio" name="is_active" value="0" id="inactive" class="ms-3" {{ !$plan->is_active ? 'checked' : '' }}>
                                            <label for="inactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                <a href="{{ route('plan.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
