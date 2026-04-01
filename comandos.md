php artisan make:model Cooperacion -m
php artisan make:model Pago -m
php artisan make:model Participantes -m
php artisan make:migration add_rol_to_users_table

php artisan migrate

php artisan make:controller CooperacionController


php artisan config:clear
php artisan cache:clear

php artisan migrate:fresh
php artisan migrate:fresh --seed





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


## Credenciales de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@coopmanager.mx | password |
| Miembro | ana@example.com | password |
| Miembro | carlos@example.com | password |

---

## Relaciones del sistema

```
users (1) ──────────────── (N) cooperaciones       [created_by]
users (1) ──────────────── (N) participantes        [user_id]
users (1) ──────────────── (N) pagos                [user_id]
cooperaciones (1) ────────── (N) participantes       [cooperacion_id]
cooperaciones (1) ────────── (N) pagos               [cooperacion_id]
```
