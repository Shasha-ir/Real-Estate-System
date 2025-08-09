use App\Models\Role;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Facades\Notification;

public function store(Request $request): RedirectResponse
{
$request->validate([
'name' => ['required', 'string', 'max:255'],
'email' => ['required', 'email', 'max:255', 'unique:users,email'],
'username' => ['required', 'string', 'max:255', 'unique:users,username'],
'role' => ['required', 'in:buyer,seller'],
'password' => ['required', 'confirmed', Rules\Password::defaults()],
]);

$role = Role::where('name', $request->role)->firstOrFail();

// Generate custom ID
$prefix = $role->name === 'seller' ? 'S' : 'B';
$count = User::where('role_id', $role->id)->count() + 1;
$customId = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

// Create user
$user = User::create([
'name' => $request->name,
'email' => $request->email,
'username' => $request->username,
'custom_id' => $customId,
'password' => Hash::make($request->password),
'role_id' => $role->id,
]);

// Fire event (for email verification if enabled)
event(new Registered($user));

// Send custom notification
$user->notify(new UserRegisteredNotification($customId));

return redirect()->route('register')->with([
'success' => 'Successfully registered! Please check your email for your login ID.',
'user_id' => $customId
]);

}