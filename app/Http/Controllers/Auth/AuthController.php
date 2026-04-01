<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    //  Login

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => [
                'required',
                'string',
                // 'email:rfc,dns',
                'email:rfc',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
            ],
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingresa un correo electrónico válido.',
            'email.max'         => 'El correo no puede tener más de 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    //  Registro

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-\.]+$/u',
            ],
            'email' => [
                'required',
                'string',
                // 'email:rfc,dns',
                'email:rfc',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->mixedCase()
                    ->uncompromised(),
            ],
        ], [
            'name.required'          => 'El nombre es obligatorio.',
            'name.min'               => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'               => 'El nombre no puede superar los 100 caracteres.',
            'name.regex'             => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'email.required'         => 'El correo electrónico es obligatorio.',
            'email.email'            => 'Ingresa un correo electrónico válido.',
            'email.max'              => 'El correo no puede tener más de 255 caracteres.',
            'email.unique'           => 'Este correo ya está registrado. ¿Olvidaste tu contraseña?',
            'password.required'      => 'La contraseña es obligatoria.',
            'password.confirmed'     => 'Las contraseñas no coinciden.',
        ]);

        $nombreLimpio = trim($request->name);
        $nombreLimpio = mb_convert_case($nombreLimpio, MB_CASE_TITLE, 'UTF-8');

        $user = User::create([
            'name'     => $nombreLimpio,
            'email'    => mb_strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'rol'      => 'miembro',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    //  Logout

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
