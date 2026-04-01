<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cooperacion;
use App\Models\Participante;
use App\Models\Pago;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //  Usuarios

        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@coopmanager.mx',
            'password' => Hash::make('password'),
            'rol'      => 'admin',
        ]);

        $miembros = collect([
            ['name' => 'Ana García',    'email' => 'ana@example.com'],
            ['name' => 'Carlos López',  'email' => 'carlos@example.com'],
            ['name' => 'María Torres',  'email' => 'maria@example.com'],
            ['name' => 'Juan Pérez',    'email' => 'juan@example.com'],
            ['name' => 'Laura Ramos',   'email' => 'laura@example.com'],
        ])->map(fn($u) => User::create([
            ...$u,
            'password' => Hash::make('password'),
            'rol'      => 'miembro',
        ]));

        //  Cooperaciones

        $coop1 = Cooperacion::create([
            'nombre'            => 'Fiesta de fin de año',
            'descripcion'       => 'Cooperación para la cena y evento de fin de año de la comunidad. Incluye cena, música y decoración.',
            'monto_objetivo'    => 5000.00,
            'monto_por_persona' => 500.00,
            'fecha_limite'      => now()->addMonths(2),
            'estado'            => 'activa',
            'created_by'        => $admin->id,
        ]);

        $coop2 = Cooperacion::create([
            'nombre'            => 'Reparación de la cancha',
            'descripcion'       => 'Fondos para reparar la cancha deportiva del vecindario. Pintura, redes y luminarias.',
            'monto_objetivo'    => 12000.00,
            'monto_por_persona' => 600.00,
            'fecha_limite'      => now()->addMonths(3),
            'estado'            => 'activa',
            'created_by'        => $admin->id,
        ]);

        $coop3 = Cooperacion::create([
            'nombre'            => 'Fondo de emergencia médica',
            'descripcion'       => 'Fondo colectivo para apoyar gastos médicos urgentes de cualquier miembro de la comunidad.',
            'monto_objetivo'    => 20000.00,
            'monto_por_persona' => 1000.00,
            'fecha_limite'      => now()->addMonths(6),
            'estado'            => 'activa',
            'created_by'        => $admin->id,
        ]);

        //  Participantes

        foreach ($miembros as $miembro) {
            // Todos participan en coop1
            Participante::create([
                'cooperacion_id' => $coop1->id,
                'user_id'        => $miembro->id,
                'monto_asignado' => $coop1->monto_por_persona,
                'estado'         => 'pendiente',
            ]);

            // Todos participan en coop2
            Participante::create([
                'cooperacion_id' => $coop2->id,
                'user_id'        => $miembro->id,
                'monto_asignado' => $coop2->monto_por_persona,
                'estado'         => 'pendiente',
            ]);

            // Solo los primeros 3 en coop3
            if ($miembros->search($miembro) < 3) {
                Participante::create([
                    'cooperacion_id' => $coop3->id,
                    'user_id'        => $miembro->id,
                    'monto_asignado' => $coop3->monto_por_persona,
                    'estado'         => 'pendiente',
                ]);
            }
        }

        //  Pagos de ejemplo

        // Ana y Carlos ya pagaron coop1
        foreach ([$miembros[0], $miembros[1]] as $m) {
            $pago = Pago::create([
                'cooperacion_id'  => $coop1->id,
                'user_id'         => $m->id,
                'monto'           => $coop1->monto_por_persona,
                'metodo_pago'     => 'efectivo',
                'estado'          => 'confirmado',
                'registrado_por'  => $admin->id,
            ]);

            // Actualizar estado del participante
            Participante::where('cooperacion_id', $coop1->id)
                ->where('user_id', $m->id)
                ->update(['estado' => 'pagado']);
        }

        // María hizo pago parcial en coop2
        Pago::create([
            'cooperacion_id'  => $coop2->id,
            'user_id'         => $miembros[2]->id,
            'monto'           => 300.00,
            'metodo_pago'     => 'transferencia',
            'estado'          => 'confirmado',
            'notas'           => 'Primer abono, resto la próxima semana',
            'registrado_por'  => $admin->id,
        ]);

        Participante::where('cooperacion_id', $coop2->id)
            ->where('user_id', $miembros[2]->id)
            ->update(['estado' => 'parcial']);

        $this->command->info('   Seeder completado.');
        $this->command->info('   Admin: admin@coopmanager.mx / password');
        $this->command->info('   Miembro: ana@example.com / password');
    }
}
