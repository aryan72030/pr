@extends('masterpage.layout')

@section('title')
    {{ __('Plans') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Plans') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Plans') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Plans List') }}</h5>
                            @permission('create-plan')
                                <a href="{{ route('plan.create') }}" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create Plan') }}">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endpermission
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Max Employees') }}</th>
                                            <th>{{ __('Max Services') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($plans as $plan)
                                            <tr>
                                                <td>{{ $plan->name }}</td>
                                                <td><span class="badge bg-{{ $plan->type == 'free' ? 'success' : 'primary' }}">{{ ucfirst($plan->type) }}</span></td>
                                                <td>{{ $plan->type == 'paid' ? '$' . number_format($plan->amount, 2) : 'Free' }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $plan->duration)) }}</td>
                                                <td>{{ $plan->max_employees }}</td>
                                                <td>{{ $plan->max_services }}</td>
                                                <td style="display: flex">
                                                    @permission('edit-plan')
                                                        <a href="{{ route('plan.edit', $plan->id) }}" class="btn btn-gradient-info">
                                                            {{ __('Edit') }}
                                                        </a>
                                                    @endpermission
                                                    @permission('delete-plan')
                                                        <form action="{{ route('plan.destroy', $plan->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button onclick="return confirm('Are you sure?')" class="btn btn-gradient-danger">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
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
