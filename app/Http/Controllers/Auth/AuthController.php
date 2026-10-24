<?php

namespace App\Http\Controllers\Auth;

use App\Support\DormStats;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Announcement;

class AuthController extends Controller
{
   
    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.index', [
        
            'view' => session('view', 'login'),
            'stats' => DormStats::get(),
             'featured' => DormStats::featuredRooms(), 
              'announcements' => Announcement::current()->take(2)->get(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = [
            'email' => trim((string) $request->input('email')),
            'password' => (string) $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return redirect()->route('login')
            ->with('error', 'Invalid email or password.')
            ->with('view', 'login');
    }

    public function signup(Request $request): RedirectResponse
    {
        $data = $request->all();
        $data['email'] = trim((string) ($data['email'] ?? ''));

        $validator = Validator::make($data, [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
            'agree' => ['accepted'],
        ], [
            'full_name.required' => 'Please enter your full name.',
            'full_name.max' => 'Please enter your full name.',
            'email.required' => 'Invalid email address.',
            'email.email' => 'Invalid email address.',
            'email.unique' => 'Email is already registered.',
            'password.required' => 'Password must be at least 8 characters.',
            'password.min' => 'Password must be at least 8 characters.',
            'confirm_password.required' => 'Passwords do not match.',
            'confirm_password.same' => 'Passwords do not match.',
            'agree.accepted' => 'You must agree to the Terms of Service and Privacy Policy.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->with('error', $validator->errors()->first())
                ->with('view', 'signup');
        }

        User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => $data['password'], // hashed by the model cast
            'role' => 'tenant',
        ]);

        return redirect()->route('login')
            ->with('success', 'Account created! You can log in now.')
            ->with('view', 'login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('tenant.home');
    }
}
