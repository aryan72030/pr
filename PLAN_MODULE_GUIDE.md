# Plan Module with Stripe Payment Gateway - Complete Implementation

## Installation Steps

### 1. Install Stripe PHP SDK
```bash
composer require stripe/stripe-php
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Configure Stripe
Add to `.env`:
```
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

### 4. Register Middleware
Add to `bootstrap/app.php` or `app/Http/Kernel.php`:
```php
'check.plan.expiry' => \App\Http\Middleware\CheckPlanExpiry::class,
```

Apply to routes that need plan checking:
```php
Route::middleware(['auth', 'check.plan.expiry'])->group(function () {
    // Your protected routes
});
```

### 5. Schedule Expired Plans Check
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('plans:check-expired')->daily();
}
```

## Features Implemented

### Admin Side

#### 1. Plan Management (CRUD)
- **Route:** `/plan`
- **Permission:** `manage-plan`, `create-plan`, `edit-plan`, `delete-plan`
- **Fields:**
  - Title (name)
  - Description
  - Type (Free/Paid) - Radio button
  - Amount (shown only if Paid)
  - Duration (Monthly, Quarterly, Half Yearly, Yearly)
  - Max Employees
  - Max Services
  - Status (Active/Inactive)

#### 2. Views
- `resources/views/plan/index.blade.php` - List all plans
- `resources/views/plan/create.blade.php` - Create plan with dynamic amount field
- `resources/views/plan/edit.blade.php` - Edit plan

### User Side

#### 1. Plan Subscription
- **Route:** `/user/plans`
- **Features:**
  - View all active plans
  - See current plan and expiry date
  - Subscribe to free plans instantly
  - Redirect to payment page for paid plans

#### 2. Stripe Payment Integration
- **Route:** `/user/plan/payment/{planId}`
- **Features:**
  - Stripe card element integration
  - Secure payment processing
  - Transaction ID storage
  - Automatic subscription creation

#### 3. Subscription History
- **Route:** `/user/plan/history`
- **Features:**
  - View all past and current subscriptions
  - See payment details
  - Track subscription status (Active/Expired/Cancelled)

### Database Structure

#### Plans Table
- id
- name (title)
- description
- type (free/paid)
- amount
- duration (monthly/quarterly/half_yearly/yearly)
- max_employees
- max_services
- is_active
- timestamps

#### Plan Subscriptions Table
- id
- user_id
- plan_id
- amount
- duration
- start_date
- end_date
- payment_method (free/stripe)
- transaction_id
- status (active/expired/cancelled)
- timestamps

#### Users Table (Added)
- plan_id
- plan_expiry_date

## Helper Functions

### Check Employee Limit
```php
if (canAddEmployee()) {
    // Add employee
} else {
    return redirect()->back()->with('error', 'Employee limit reached');
}
```

### Check Service Limit
```php
if (canAddService()) {
    // Add service
} else {
    return redirect()->back()->with('error', 'Service limit reached');
}
```

### Get Remaining Slots
```php
$remaining = getRemainingEmployeeSlots();
```

## Middleware Usage

### CheckPlanExpiry Middleware
Automatically redirects users with expired plans to the plan page with a toaster message.

Apply to routes:
```php
Route::middleware(['auth', 'check.plan.expiry'])->group(function () {
    Route::resource('employes', EmployesController::class);
    Route::resource('service', ServiceController::class);
    // Other protected routes
});
```

## Subscription Flow

### Free Plan
1. User clicks "Subscribe" on free plan
2. Subscription created immediately
3. Plan assigned to user with expiry date
4. Redirect to plans page with success message

### Paid Plan
1. User clicks "Subscribe" on paid plan
2. Redirect to payment page
3. User enters card details (Stripe Elements)
4. Payment processed via Stripe
5. On success:
   - Subscription created with transaction ID
   - Plan assigned to user with expiry date
   - Redirect to plans page with success message
6. On failure:
   - Show error message
   - User can retry payment

## Duration Calculation
- **Monthly:** +1 month
- **Quarterly:** +3 months
- **Half Yearly:** +6 months
- **Yearly:** +1 year

## Automatic Expiry Management

### Command
```bash
php artisan plans:check-expired
```

### Scheduled (Daily)
Automatically runs daily to mark expired subscriptions.

### Manual Check
User model has `isPlanExpired()` method that checks in real-time.

## Usage in Controllers

### Employee Controller Example
```php
public function store(Request $request)
{
    if (!canAddEmployee()) {
        return redirect()->back()->with('error', 'You have reached your employee limit. Please upgrade your plan.');
    }
    
    // Create employee
}
```

### Service Controller Example
```php
public function store(Request $request)
{
    if (!canAddService()) {
        return redirect()->back()->with('error', 'You have reached your service limit. Please upgrade your plan.');
    }
    
    // Create service
}
```

## Testing

### Test Stripe Cards
- Success: 4242 4242 4242 4242
- Decline: 4000 0000 0000 0002
- Use any future expiry date and any 3-digit CVC

## Security Notes

1. Stripe keys are loaded from settings (database)
2. Call `stripe_config()` before Stripe operations
3. Transaction IDs are stored for reference
4. Payment method is tracked (free/stripe)

## Menu Items

### Admin
- Plans (sidebar) - Manage all plans

### User
- My Plans (sidebar) - View and subscribe to plans
- Subscription History (accessible from plans page)

## Permissions Required

Add to PermissionSeeder (already added):
- manage-plan
- create-plan
- edit-plan
- delete-plan
- view-plan

## Next Steps

1. Run migrations
2. Install Stripe package
3. Configure Stripe settings in admin panel
4. Create plans
5. Test subscription flow
6. Apply middleware to protected routes
7. Add plan limit checks in employee/service controllers
