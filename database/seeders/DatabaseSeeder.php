<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'demo@studentnotepad.ai'],
            [
                'name' => 'Estudiante Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $subjects = [];
        $subjectData = [
            ['name' => 'Cálculo', 'description' => 'Límites, derivadas e integrales.'],
            ['name' => 'Física', 'description' => 'Cinemática, dinámica y energía.'],
            ['name' => 'Historia', 'description' => 'Edad contemporánea y revoluciones.'],
        ];

        foreach ($subjectData as $data) {
            $subjects[$data['name']] = Subject::query()->firstOrCreate(
                ['user_id' => $user->id, 'name' => $data['name']],
                ['description' => $data['description']],
            );
        }

        $notes = [
            [
                'title' => 'Derivadas básicas',
                'subject' => 'Cálculo',
                'raw_content' => "# Derivadas\n\n- Definición: tasa de cambio instantánea.\n- Notación: f'(x).\n\n## Reglas\n1. Constante → 0\n2. Potencia → n*x^(n-1)\n3. Suma → derivar término a término\n\nEjemplo: f(x)=x^2, entonces f'(x)=2x.",
                'ai_content' => "# Derivadas básicas\n\n## Ideas clave\n- Miden la tasa de cambio instantánea.\n- Se representan como f'(x).\n- Hay reglas simples para potencias y sumas.\n\n## Desarrollo\nPara derivar constantes se obtiene 0. Para una potencia x^n, el resultado es n*x^(n-1). Las sumas se derivan por separado. Un ejemplo clásico es f(x)=x^2 → f'(x)=2x.\n\n## Resumen\nLas reglas de derivación permiten calcular rápidamente tasas de cambio de funciones sencillas.",
                'status' => 'ready',
            ],
            [
                'title' => 'Leyes de Newton',
                'subject' => 'Física',
                'raw_content' => "# Leyes de Newton\n\n- 1ª ley: inercia.\n- 2ª ley: F = m*a.\n- 3ª ley: acción y reacción.\n\n## Ejemplo\nUn bloque de 2kg con fuerza de 6N tiene a = 3 m/s^2.",
                'ai_content' => "# Leyes de Newton\n\n## Ideas clave\n- La inercia mantiene el estado de movimiento.\n- La fuerza neta produce aceleración.\n- Las fuerzas siempre aparecen en pares.\n\n## Desarrollo\nLa segunda ley relaciona fuerza y aceleración mediante F = m*a. En el ejemplo, a = 6N / 2kg = 3 m/s^2.\n\n## Resumen\nLas tres leyes describen cómo las fuerzas afectan el movimiento de los cuerpos.",
                'status' => 'ready',
            ],
            [
                'title' => 'Revolución Industrial',
                'subject' => 'Historia',
                'raw_content' => "# Revolución Industrial\n\n## Causas\n- Innovación tecnológica.\n- Capital disponible.\n- Mano de obra urbana.\n\n## Consecuencias\n- Urbanización rápida.\n- Cambios en el trabajo.\n- Nuevas clases sociales.",
                'ai_content' => "# Revolución Industrial\n\n## Ideas clave\n- Transformó la producción con maquinaria.\n- Cambió la organización social y laboral.\n\n## Desarrollo\nEl acceso a capital, tecnologías y mano de obra permitió el salto hacia la industrialización. Las ciudades crecieron y aparecieron nuevas relaciones entre empleadores y trabajadores.\n\n## Resumen\nFue un cambio económico y social profundo que sentó las bases de la industria moderna.",
                'status' => 'ready',
            ],
            [
                'title' => 'Integrales inmediatas',
                'subject' => 'Cálculo',
                'raw_content' => "# Integrales\n\n- ∫ x^n dx = x^(n+1)/(n+1) + C\n- ∫ 1/x dx = ln|x| + C\n\nPendiente: revisar ejemplos.",
                'ai_content' => null,
                'status' => 'processing',
            ],
            [
                'title' => 'Apuntes de estudio',
                'subject' => null,
                'raw_content' => "# Plan semanal\n\n- Lunes: repasar fórmulas.\n- Miércoles: ejercicios.\n- Viernes: resumen.\n\n> No olvidar practicar.",
                'ai_content' => null,
                'status' => 'failed',
            ],
        ];

        $hasLegacyContent = Schema::hasColumn('notes', 'content');

        foreach ($notes as $noteData) {
            $subjectId = $noteData['subject']
                ? ($subjects[$noteData['subject']]->id ?? null)
                : null;

            $payload = [
                'subject_id' => $subjectId,
                'raw_content' => $noteData['raw_content'],
                'ai_content' => $noteData['ai_content'],
                'status' => $noteData['status'],
            ];

            if ($hasLegacyContent) {
                $payload['content'] = $noteData['raw_content'];
            }

            Note::query()->firstOrCreate(
                ['user_id' => $user->id, 'title' => $noteData['title']],
                $payload,
            );
        }
    }
}
