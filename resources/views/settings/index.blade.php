@extends('masterpage.layout')
@section('title', 'Settings')

@section('mainConten')
<div class="dash-container">
    <div class="dash-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">{{ __('Settings') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="list-group list-group-flush" id="settings-nav">
                        <a class="list-group-item list-group-item-action" href="#email-settings">{{ __('Email Settings') }}</a>
                        <a class="list-group-item list-group-item-action" href="#payment-settings">{{ __('Payment Settings') }}</a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-9">
                <div id="email-settings" class="card mb-4">
                    <div class="card-header">
                        <h5>{{ __('Email Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.email.update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Mailer') }}</label>
                                <input type="text" name="mail_mailer" class="form-control" value="{{ old('mail_mailer', $emailSettings['mail_mailer'] ?? 'smtp') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Host') }}</label>
                                <input type="text" name="mail_host" class="form-control" value="{{ old('mail_host', $emailSettings['mail_host'] ?? '') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Port') }}</label>
                                <input type="text" name="mail_port" class="form-control" value="{{ old('mail_port', $emailSettings['mail_port'] ?? '') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Username') }}</label>
                                <input type="text" name="mail_username" class="form-control" value="{{ old('mail_username', $emailSettings['mail_username'] ?? '') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Password') }}</label>
                                <input type="password" name="mail_password" class="form-control" value="{{ old('mail_password', $emailSettings['mail_password'] ?? '') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('Mail Encryption') }}</label>
                                <select name="mail_encryption" class="form-control">
                                    <option value="tls" {{ (old('mail_encryption', $emailSettings['mail_encryption'] ?? '') == 'tls') ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ (old('mail_encryption', $emailSettings['mail_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="payment-settings" class="card mb-4">
                    <div class="card-header">
                        <h5>{{ __('Payment Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h5>{{ __('Basic') }}</h5>
                                        <h2 class="my-3">$9<small>/month</small></h2>
                                        <ul class="list-unstyled">
                                            <li>5 Employees</li>
                                            <li>10GB Storage</li>
                                            <li>Basic Support</li>
                                        </ul>
                                        <button class="btn btn-primary">{{ __('Subscribe') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h5>{{ __('Standard') }}</h5>
                                        <h2 class="my-3">$29<small>/month</small></h2>
                                        <ul class="list-unstyled">
                                            <li>20 Employees</li>
                                            <li>50GB Storage</li>
                                            <li>Priority Support</li>
                                        </ul>
                                        <button class="btn btn-primary">{{ __('Subscribe') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h5>{{ __('Professional') }}</h5>
                                        <h2 class="my-3">$59<small>/month</small></h2>
                                        <ul class="list-unstyled">
                                            <li>50 Employees</li>
                                            <li>200GB Storage</li>
                                            <li>24/7 Support</li>
                                        </ul>
                                        <button class="btn btn-primary">{{ __('Subscribe') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h5>{{ __('Enterprise') }}</h5>
                                        <h2 class="my-3">$99<small>/month</small></h2>
                                        <ul class="list-unstyled">
                                            <li>Unlimited Employees</li>
                                            <li>Unlimited Storage</li>
                                            <li>Dedicated Support</li>
                                        </ul>
                                        <button class="btn btn-primary">{{ __('Subscribe') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#settings-nav',
        offset: 100
    });
});
</script>
@endsection
