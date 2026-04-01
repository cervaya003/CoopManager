<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Iniciar sesión · CoopManager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a; --ink-2: #334155; --ink-3: #64748b;
            --surface: #f8fafc; --card: #ffffff; --border: #e2e8f0;
            --accent: #059669; --danger: #dc2626; --radius: 12px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        /* Fondo con patrón sutil */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle at 25% 25%, #1e293b 0%, transparent 50%),
                              radial-gradient(circle at 75% 75%, #065f46 0%, transparent 50%);
            opacity: .4;
            pointer-events: none;
        }
        .auth-wrap {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: #fff;
            letter-spacing: -.5px;
        }
        .brand-sub {
            font-size: .78rem;
            color: #64748b;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
        }
        .card h2 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .card p {
            font-size: .85rem;
            color: var(--ink-3);
            margin-bottom: 24px;
        }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--ink-2); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: .9rem; font-family: inherit; color: var(--ink);
            background: var(--surface); outline: none; transition: border-color .15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(5,150,105,.1); }
        .form-control.error { border-color: var(--danger); }
        .error-msg { font-size: .75rem; color: var(--danger); margin-top: 4px; }
        .btn-submit {
            width: 100%; padding: 11px;
            background: var(--accent); color: #fff;
            border: none; border-radius: 8px;
            font-size: .92rem; font-weight: 600;
            cursor: pointer; font-family: inherit;
            transition: background .15s;
            margin-top: 8px;
        }
        .btn-submit:hover { background: #047857; }
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: .82rem;
            color: #64748b;
        }
        .auth-footer a { color: #34d399; text-decoration: none; font-weight: 600; }
        .checkbox-row {
            display: flex; align-items: center; gap: 8px;
            font-size: .82rem; color: var(--ink-2); margin-bottom: 16px;
        }
        .checkbox-row input { accent-color: var(--accent); width: 15px; height: 15px; }
        .alert-error {
            background: #fee2e2; color: #991b1b;
            border: 1px solid #fca5a5; border-radius: 8px;
            padding: 10px 14px; font-size: .83rem; margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    <div class="brand">
        <div class="brand-name">CoopManager</div>
        <div class="brand-sub">Sistema de cooperaciones</div>
    </div>

    <div class="card">
        <h2>Bienvenido</h2>
        <p>Inicia sesión para gestionar tus cooperaciones</p>

        @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="tu@correo.com">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                       required autocomplete="current-password" placeholder="••••••••">
                @error('password')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="checkbox-row">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>
    </div>

    <div class="auth-footer">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
    </div>
</div>

</body>
</html>
