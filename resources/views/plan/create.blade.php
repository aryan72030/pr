@extends('masterpage.layout')

@section('title')
    {{ __('Create Plan') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Create Plan') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('plan.index') }}">{{ __('Plans') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Create') }}</li>
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
                            <form action="{{ route('plan.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Title') }}</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter plan title" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Duration') }}</label>
                                        <select name="duration" class="form-control" required>
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="half_yearly">Half Yearly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-label">{{ __('Description') }}</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Type') }}</label>
                                        <div>
                                            <input type="radio" name="type" value="free" id="free" onchange="toggleAmount()">
                                            <label for="free">Free</label>
                                            <input type="radio" name="type" value="paid" id="paid" class="ms-3" checked onchange="toggleAmount()">
                                            <label for="paid">Paid</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" id="amountField">
                                        <label class="form-label">{{ __('Amount') }}</label>
                                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter amount">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Max Employees') }}</label>
                                        <input type="number" name="max_employees" class="form-control" placeholder="Enter max employees" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Max Services') }}</label>
                                        <input type="number" name="max_services" class="form-control" placeholder="Enter max services" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">{{ __('Status') }}</label>
                                        <div>
                                            <input type="radio" name="is_active" value="1" id="active" checked>
                                            <label for="active">Active</label>
                                            <input type="radio" name="is_active" value="0" id="inactive" class="ms-3">
                                            <label for="inactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                <a href="{{ route('plan.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleAmount() {
            const type = document.querySelector('input[name="type"]:checked').value;
            const amountField = document.getElementById('amountField');
            const amountInput = document.querySelector('input[name="amount"]');
            
            if (type === 'free') {
                amountField.style.display = 'none';
                amountInput.removeAttribute('required');
                amountInput.value = '';
            } else {
                amountField.style.display = 'block';
                amountInput.setAttribute('required', 'required');
            }
        }
    </script>
@endsection
