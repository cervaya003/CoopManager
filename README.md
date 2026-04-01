<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

URL sistema de cooperaciones:
https://coopmanager-main-bt5rgg.free.laravel.cloud/login 

## Estructura de archivos generados

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php        
│   │   ├── CooperacionController.php        
│   │   ├── ParticipanteController.php      
│   │   ├── PagoController.php               
│   │   └── DashboardController.php          
│   └── Middleware/
│       └── AdminMiddleware.php             
├── Models/
│   ├── User.php                            
│   ├── Cooperacion.php                      
│   ├── Participante.php                     
│   └── Pago.php                             
database/
├── migrations/
│   ├── ..._create_users_table.php           
│   ├── ..._add_rol_to_users_table.php       
│   ├── ..._create_cooperacions_table_UPDATED.php  
│   ├── ..._create_pagos_table_UPDATED.php        
│   └── ..._create_participantes_table.php   
└── seeders/
    └── DatabaseSeeder.php                   
resources/views/
├── layouts/
│   └── app.blade.php                        
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── dashboard/
│   └── index.blade.php
├── cooperaciones/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
└── pagos/
    └── index.blade.php
routes/
└── web.php                                
bootstrap/
└── app.php                                
```


Sistema de Cooperaciones Comunitarias (SaaS)
Se desarrollará una aplicación web tipo SaaS orientada a resolver la gestión de cooperaciones económicas en comunidades, donde actualmente el control se realiza de forma manual mediante libretas o registros informales, lo que genera pérdida de información, falta de transparencia y errores en el seguimiento de pagos.
El sistema permitirá a los usuarios crear, administrar y dar seguimiento a cooperaciones (colectas de dinero) de forma digital, centralizada y confiable.
Funcionalidades principales:
1. Gestión de cooperaciones: El sistema permitirá crear cooperaciones definiendo nombre, descripción, monto objetivo, monto por persona y fecha límite. Cada cooperación estará asociada a un usuario creador.
2. Registro de participantes: Se podrá llevar un control de las personas que participan en la cooperación, permitiendo identificar quiénes deben aportar y cuánto corresponde a cada uno.
3. Registro de pagos: Los usuarios podrán registrar pagos realizados por cada participante, indicando monto, fecha y relación con la cooperación correspondiente.
4. Control de estado: El sistema calculará automáticamente:
* Total recaudado
* Monto restante
* Participantes que ya pagaron
* Participantes pendientes
1. Visualización y seguimiento: Cada cooperación tendrá una vista detallada donde se mostrará el progreso, historial de pagos y estado general.
2. Transparencia: Todos los datos estarán disponibles en tiempo real para evitar conflictos o confusiones dentro de la comunidad.


* Patrón MVC (Modelo - Vista - Controlador)
* Relación entre entidades:
   * Usuario → Cooperaciones (1:N)
   * Cooperación → Pagos (1:N)
   * Usuario → Pagos (1:N)

Objetivo del sistema:
Digitalizar y profesionalizar la gestión de cooperaciones comunitarias, proporcionando una herramienta simple, accesible y confiable que elimine el uso de papel y reduzca errores humanos.
Visión a futuro:
Escalar el sistema como una plataforma SaaS multiusuario donde diferentes comunidades puedan gestionar sus propias cooperaciones, incluyendo:
* Autenticación completa
* Roles (administrador, participante)
* Pagos
Este proyecto busca evolucionar de una solución local a un producto escalable con potencial comercial.




## Credenciales de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@coopmanager.mx | password |
| Miembro | ana@example.com | password |
| Miembro | carlos@example.com | password |



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

