@extends('masterpage.layout')

@section('title')
    {{ __('Subscription History') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Subscription History') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.plans') }}">{{ __('Plans') }}</a></li>
                                <li class="breadcrumb-item">{{ __('History') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Your Subscriptions') }}</h5>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Plan') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Transaction ID') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Invoice') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscriptions as $subscription)
                                            <tr>
                                                <td>{{ $subscription->plan->name }}</td>
                                                <td>${{ number_format($subscription->amount, 2) }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $subscription->duration)) }}</td>
                                                <td>{{ $subscription->start_date->format('d M Y') }}</td>
                                                <td>{{ $subscription->end_date->format('d M Y') }}</td>
                                                <td>{{ ucfirst($subscription->payment_method) }}</td>
                                                <td>{{ $subscription->transaction_id ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'expired' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($subscription->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.plan.invoice', $subscription->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                        <i class="ti ti-file-invoice"></i> Invoice
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No subscription history found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
