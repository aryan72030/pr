# Integration Examples - Add Plan Limits to Existing Controllers

## 1. Employee Controller Integration

Update `app/Http/Controllers/EmployesController.php`:

```php
public function create()
{
    if (!canAddEmployee()) {
        return redirect()->route('employes.index')
            ->with('error', 'You have reached your employee limit (' . Auth::user()->plan->max_employees . '). Please upgrade your plan.');
    }
    
    // Existing code...
}

public function store(Request $request)
{
    if (!canAddEmployee()) {
        return redirect()->route('employes.index')
            ->with('error', 'You have reached your employee limit. Please upgrade your plan.');
    }
    
    // Existing validation and store code...
}
```

## 2. Service Controller Integration

Update `app/Http/Controllers/ServiceController.php`:

```php
public function create()
{
    if (!canAddService()) {
        return redirect()->route('service.index')
            ->with('error', 'You have reached your service limit (' . Auth::user()->plan->max_services . '). Please upgrade your plan.');
    }
    
    // Existing code...
}

public function store(Request $request)
{
    if (!canAddService()) {
        return redirect()->route('service.index')
            ->with('error', 'You have reached your service limit. Please upgrade your plan.');
    }
    
    // Existing validation and store code...
}
```

## 3. Apply Middleware to Routes

Update `routes/web.php`:

```php
Route::middleware(['auth', 'check.plan.expiry'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('employes', EmployesController::class);
    Route::resource('role', RoleController::class);
    Route::resource('blog', BlogController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('staffAvailability', StaffAvailabilityController::class);
    Route::resource('appointment', AppointmentController::class);
    Route::resource('plan', PlanController::class);
    Route::resource('email', SettingEmailController::class);
    Route::resource('stripe', SettingStripeController::class);
});

// User plan routes (no expiry check needed)
Route::middleware('auth')->group(function () {
    Route::get('user/plans', [UserPlanController::class, 'index'])->name('user.plans');
    Route::post('user/plan/subscribe/{planId}', [UserPlanController::class, 'subscribe'])->name('user.plan.subscribe');
    Route::post('user/plan/payment/{planId}', [UserPlanController::class, 'processPayment'])->name('user.plan.payment');
    Route::get('user/plan/history', [UserPlanController::class, 'history'])->name('user.plan.history');
});
```

## 4. Display Plan Info in Dashboard

Update `resources/views/dashboard.blade.php`:

```blade
@if(Auth::user()->plan)
    <div class="alert alert-info">
        <strong>Current Plan:</strong> {{ Auth::user()->plan->name }}
        <br>
        <strong>Employees:</strong> {{ getRemainingEmployeeSlots() }} / {{ Auth::user()->plan->max_employees }} remaining
        <br>
        <strong>Expires:</strong> {{ Auth::user()->plan_expiry_date ? Auth::user()->plan_expiry_date->format('d M Y') : 'N/A' }}
        @if(Auth::user()->isPlanExpired())
            <br>
            <span class="text-danger"><strong>Your plan has expired!</strong></span>
            <a href="{{ route('user.plans') }}" class="btn btn-sm btn-danger">Renew Now</a>
        @endif
    </div>
@else
    <div class="alert alert-warning">
        <strong>No Active Plan</strong>
        <br>
        <a href="{{ route('user.plans') }}" class="btn btn-sm btn-primary">Choose a Plan</a>
    </div>
@endif
```

## 5. Show Limits in Employee Index

Update `resources/views/employess/index.blade.php` (add before table):

```blade
@if(Auth::user()->plan)
    <div class="alert alert-info">
        <i class="ti ti-info-circle"></i>
        <strong>Plan Limit:</strong> {{ getRemainingEmployeeSlots() }} of {{ Auth::user()->plan->max_employees }} employee slots remaining
    </div>
@endif
```

## 6. Show Limits in Service Index

Update `resources/views/service/index.blade.php` (add before table):

```blade
@if(Auth::user()->plan)
    <div class="alert alert-info">
        <i class="ti ti-info-circle"></i>
        <strong>Plan Limit:</strong> You can create {{ Auth::user()->plan->max_services }} services
    </div>
@endif
```

## 7. Register Middleware

### For Laravel 11+ (bootstrap/app.php)
```php
use App\Http\Middleware\CheckPlanExpiry;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.plan.expiry' => CheckPlanExpiry::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### For Laravel 10 and below (app/Http/Kernel.php)
```php
protected $middlewareAliases = [
    // ... existing middleware
    'check.plan.expiry' => \App\Http\Middleware\CheckPlanExpiry::class,
];
```

## 8. Schedule Task

Update `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('plans:check-expired')->daily();
}
```

Then run:
```bash
php artisan schedule:work
```

Or add to crontab:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 9. Display Success/Error Messages

Make sure your layout has flash message display:

```blade
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

## 10. Test Checklist

- [ ] Create free plan and subscribe
- [ ] Create paid plan and test Stripe payment
- [ ] Try adding employee beyond limit
- [ ] Try adding service beyond limit
- [ ] Test plan expiry (manually set past date)
- [ ] Check subscription history
- [ ] Test middleware redirect on expired plan
- [ ] Run `php artisan plans:check-expired` command
- [ ] Verify Stripe settings work from admin panel
