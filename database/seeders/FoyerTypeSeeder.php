<?php

namespace Database\Seeders;

use App\Models\FoyerType;
use Illuminate\Database\Seeder;

class FoyerTypeSeeder extends Seeder
{
    public function run(): void
    {
        $foyers = [
            [
                'code' => 'JAMBAR_JEEG_CHARBON_4KG',
                'nom' => 'Jambar simple / Tak si Rip JEEG (CHARBON-KERIGNE)',
                'combustible' => 'Charbon',
                'capacite_kg' => '4 kg',
                'description' => 'Foyer amélioré au charbon pour petits ménages (4 kg)',
                'ordre' => 1,
            ],
            [
                'code' => 'JAMBAR_JABOOT_CHARBON_7KG',
                'nom' => 'Jambar simple / Tak Si Rip JABOOT (CHARBON-KERIGNE)',
                'combustible' => 'Charbon',
                'capacite_kg' => '7 kg',
                'description' => 'Foyer amélioré au charbon pour ménages moyens (7 kg)',
                'ordre' => 2,
            ],
            [
                'code' => 'JAMBAR_JONGOMA_CHARBON_10KG',
                'nom' => 'Jambar simple JONGOMA (CHARBON-KERIGNE)',
                'combustible' => 'Charbon',
                'capacite_kg' => '10 kg',
                'description' => 'Foyer amélioré au charbon grande contenance (10 kg)',
                'ordre' => 3,
            ],
            [
                'code' => 'TAKSIRIP_JONGOMA_CHARBON_15KG',
                'nom' => 'Tak Si Rip JONGOMA (CHARBON-KERIGNE)',
                'combustible' => 'Charbon',
                'capacite_kg' => '15 kg',
                'description' => 'Foyer amélioré au charbon extra large (15 kg)',
                'ordre' => 4,
            ],
            [
                'code' => 'JAMBAR_JEEG_BOIS_4KG',
                'nom' => 'Jambar JEEG (BOIS-MATT)',
                'combustible' => 'Bois',
                'capacite_kg' => '4 kg',
                'description' => 'Foyer amélioré au bois économe (4 kg)',
                'ordre' => 5,
            ],
            [
                'code' => 'JAMBAR_JABOOT_BOIS_7KG',
                'nom' => 'Jambar JABOOT (BOIS-MATT)',
                'combustible' => 'Bois',
                'capacite_kg' => '7 kg',
                'description' => 'Foyer amélioré au bois haute efficacité ménages moyens (7 kg)',
                'ordre' => 6,
            ],
            [
                'code' => 'JAMBAR_JONGOMA_BOIS_10KG',
                'nom' => 'Jambar JONGOMA (BOIS-MATT)',
                'combustible' => 'Bois',
                'capacite_kg' => '10 kg',
                'description' => 'Foyer amélioré au bois grande capacité (10 kg)',
                'ordre' => 7,
            ],
            [
                'code' => 'TAARU_CHARBON_7KG',
                'nom' => 'TAARU (CHARBON)',
                'combustible' => 'Charbon',
                'capacite_kg' => '7 kg',
                'description' => 'Modèle esthétique et thermique TAARU (7 kg)',
                'ordre' => 8,
            ],
            [
                'code' => 'SAKKANAL_MULTI_4_7KG',
                'nom' => 'Sakkanal multi marmites (Charbon et bois)',
                'combustible' => 'Bois/Charbon',
                'capacite_kg' => '4 à 7 kg',
                'description' => 'Foyer polyvalent multi-marmites charbon & bois',
                'ordre' => 9,
            ],
            [
                'code' => 'SAKKANAL_MONO_7KG',
                'nom' => 'Sakkanal mono marmites (Charbon et bois)',
                'combustible' => 'Bois/Charbon',
                'capacite_kg' => '7 kg',
                'description' => 'Foyer polyvalent mono marmite charbon & bois (7 kg)',
                'ordre' => 10,
            ],
        ];

        foreach ($foyers as $foyer) {
            FoyerType::updateOrCreate(['code' => $foyer['code']], $foyer);
        }
    }
}
