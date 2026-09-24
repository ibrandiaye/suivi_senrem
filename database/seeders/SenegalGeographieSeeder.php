<?php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\Departement;
use App\Models\Region;
use Illuminate\Database\Seeder;

class SenegalGeographieSeeder extends Seeder
{
    public function run(): void
    {
        $geo = [
            'Dakar' => [
                'code' => 'DK',
                'departements' => [
                    'Dakar' => ['Plateau', 'Médina', 'Grand Dakar', 'Parcelles Assainies', 'Almadies', 'Ouakam', 'Ngor', 'Yoff'],
                    'Pikine' => ['Pikine Nord', 'Pikine Ouest', 'Pikine Est', 'Thiaroye', 'Mbao', 'Keur Massar Nord'],
                    'Guédiawaye' => ['Golf Sud', 'Sam Notaire', 'Ndiarème Limamoulaye', 'Wakhinane Nimzatt', 'Médina Gounass'],
                    'Rufisque' => ['Rufisque Est', 'Rufisque Ouest', 'Rufisque Nord', 'Bargny', 'Sébikotane', 'Sendou', 'Sangalkam', 'Diamniadio'],
                    'Keur Massar' => ['Keur Massar Nord', 'Keur Massar Sud', 'Malika', 'Yeumbeul Nord', 'Yeumbeul Sud', 'Jaxaay-Parcelle'],
                ],
            ],
            'Thiès' => [
                'code' => 'TH',
                'departements' => [
                    'Thiès' => ['Thiès Ville', 'Thiès Nord', 'Thiès Est', 'Thiès Ouest', 'Khombole', 'Pout', 'Notto', 'Fandène', 'Kayar'],
                    'Mbour' => ['Mbour Ville', 'Saly Portudal', 'Nguékhokh', 'Joal-Fadiouth', 'Popenguine', 'Somalone', 'Malicounda', 'Sindia'],
                    'Tivaouane' => ['Tivaouane Ville', 'Méouane', 'Niakhène', 'Pambal', 'Mékhé', 'Pékesse', 'Pire Goureye'],
                ],
            ],
            'Diourbel' => [
                'code' => 'DB',
                'departements' => [
                    'Diourbel' => ['Diourbel Ville', 'Ndindy', 'Ndoulo', 'Touré Mbonde', 'Taïba Moutoupha'],
                    'Bambey' => ['Bambey Ville', 'Baba Garage', 'Lambaye', 'Ngoye', 'Dinguiraye'],
                    'Mbacké' => ['Mbacké Ville', 'Touba Mosquée', 'Dalla Ngabou', 'Kael', 'Madina', 'Taïba Thiékène'],
                ],
            ],
            'Fatick' => [
                'code' => 'FK',
                'departements' => [
                    'Fatick' => ['Fatick Ville', 'Diofior', 'Diakhao', 'Fimela', 'Niakhar', 'Tattaguine'],
                    'Foundiougne' => ['Foundiougne Ville', 'Passy', 'Sokone', 'Toubacouta', 'Djilor', 'Karang Poste'],
                    'Gossas' => ['Gossas Ville', 'Colobane', 'Mbar', 'Ouadiour', 'Patar Lia'],
                ],
            ],
            'Kaolack' => [
                'code' => 'KL',
                'departements' => [
                    'Kaolack' => ['Kaolack Ville', 'Kahone', 'Ndoffane', 'Koumbal', 'Latmingué', 'Thiaré'],
                    'Guinguinéo' => ['Guinguinéo Ville', 'Fass', 'Mboss', 'Nguélou', 'Khelcom Birane'],
                    'Nioro du Rip' => ['Nioro Ville', 'Keur Madiabel', 'Medina Sabakh', 'Paoskoto', 'Wack Ngouna', 'Porokhane'],
                ],
            ],
            'Kaffrine' => [
                'code' => 'KF',
                'departements' => [
                    'Kaffrine' => ['Kaffrine Ville', 'Nganda', 'Diamagadio', 'Gniby', 'Kahi'],
                    'Birkelane' => ['Birkelane Ville', 'Keur Mboucki', 'Mabo', 'Touba Mbella'],
                    'Koungheul' => ['Koungheul Ville', 'Ida Mouride', 'Lour Escale', 'Ribo Escale', 'Saly Escale'],
                    'Malem Hodar' => ['Malem Hodar Ville', 'Darou Minam', 'Djida', 'Ndioum Ngainthe'],
                ],
            ],
            'Louga' => [
                'code' => 'LG',
                'departements' => [
                    'Louga' => ['Louga Ville', 'Coki', 'Keur Momar Sarr', 'Sakal', 'Koki'],
                    'Kébémer' => ['Kébémer Ville', 'Darou Mousty', 'Guéoul', 'Ndande', 'Sagatta Gueth'],
                    'Linguère' => ['Linguère Ville', 'Dahra', 'Barkedji', 'Dodji', 'Yang-Yang', 'Gassane'],
                ],
            ],
            'Saint-Louis' => [
                'code' => 'SL',
                'departements' => [
                    'Saint-Louis' => ['Saint-Louis Ville', 'Mpal', 'Gandon', 'Fass Ngom'],
                    'Dagana' => ['Dagana Ville', 'Richard-Toll', 'Rosso-Sénégal', 'Ross Béthio', 'Gaé', 'Ronkh'],
                    'Podor' => ['Podor Ville', 'Ndioum', 'Guédé Chantier', 'Démette', 'Golléré', 'Pété', 'Galoya Toucouleur'],
                ],
            ],
            'Matam' => [
                'code' => 'MT',
                'departements' => [
                    'Matam' => ['Matam Ville', 'Ourossogui', 'Nguidjilone', 'Ogo', 'Dabia'],
                    'Kanel' => ['Kanel Ville', 'Semmé', 'Dembancané', 'Hamady Hounaré', 'Waoundé', 'Orkadiere'],
                    'Ranérou Ferlo' => ['Ranérou Ville', 'Vélingara Ferlo', 'Lougré Thioly', 'Oudalaye'],
                ],
            ],
            'Tambacounda' => [
                'code' => 'TC',
                'departements' => [
                    'Tambacounda' => ['Tambacounda Ville', 'Koussanar', 'Makacolibantang', 'Missirah', 'Dialacoto'],
                    'Bakel' => ['Bakel Ville', 'Diawara', 'Kidira', 'Bélé', 'Gathiary', 'Moudéry', 'Sadatou'],
                    'Goudiry' => ['Goudiry Ville', 'Bala', 'Boynguel Bamba', 'Dianké Makha', 'Koar', 'Kothiary'],
                    'Koumpentoum' => ['Koumpentoum Ville', 'Malem Niani', 'Bamba Thialène', 'Kouthiaba Wolof', 'Pass Koto'],
                ],
            ],
            'Kédougou' => [
                'code' => 'KD',
                'departements' => [
                    'Kédougou' => ['Kédougou Ville', 'Bandafassi', 'Dindéfélo', 'Fongolimbi', 'Ninéfécha'],
                    'Salémata' => ['Salémata Ville', 'Dakateli', 'Kévoye', 'Oubadji'],
                    'Saraya' => ['Saraya Ville', 'Bembou', 'Khossanto', 'Médina Baffé', 'Sabodala'],
                ],
            ],
            'Kolda' => [
                'code' => 'KL2',
                'departements' => [
                    'Kolda' => ['Kolda Ville', 'Dabo', 'Salikégné', 'Saré Bidji', 'Bagadadji', 'Coumbacara', 'Dioulacolon', 'Guiro Yero Bocar', 'Mampatim'],
                    'Médina Yoro Foulah' => ['Médina Yoro Foulah Ville', 'Pata', 'Fafacourou', 'Bourouco', 'Koulinto', 'Ndorna'],
                    'Vélingara' => ['Vélingara Ville', 'Kounkané', 'Diaobé-Kabendou', 'Bonconto', 'Kandia', 'Linkéring', 'Pakour', 'Saré Coly Sallé'],
                ],
            ],
            'Sédhiou' => [
                'code' => 'SD',
                'departements' => [
                    'Sédhiou' => ['Sédhiou Ville', 'Marsassoum', 'Diannah Malary', 'Djibabouya', 'Oudoucar', 'Sama Kanta Peulh'],
                    'Bounkiling' => ['Bounkiling Ville', 'Madina Wandifa', 'Boghal', 'Bona', 'Diacounda', 'Tankon'],
                    'Goudomp' => ['Goudomp Ville', 'Samine', 'Tanaff', 'Diattacounda', 'Kaour', 'Simbandi Brassou'],
                ],
            ],
            'Ziguinchor' => [
                'code' => 'ZG',
                'departements' => [
                    'Ziguinchor' => ['Ziguinchor Ville', 'Niaguis', 'Enampore', 'Adéane', 'Boutoupa-Camaracounda'],
                    'Bignona' => ['Bignona Ville', 'Thionck Essyl', 'Diouloulou', 'Balingore', 'Djibidione', 'Kataba 1', 'Kafountine', 'Sindian', 'Tendouck'],
                    'Oussouye' => ['Oussouye Ville', 'Cabrousse', 'Cap Skirring', 'Diembéring', 'Santhiaba Manjack', 'Mlomp'],
                ],
            ],
        ];

        foreach ($geo as $nomRegion => $data) {
            $region = Region::firstOrCreate(
                ['nom' => $nomRegion],
                ['code' => $data['code']]
            );

            foreach ($data['departements'] as $nomDept => $communesList) {
                $departement = Departement::firstOrCreate([
                    'region_id' => $region->id,
                    'nom' => $nomDept,
                ]);

                foreach ($communesList as $nomCommune) {
                    Commune::firstOrCreate([
                        'departement_id' => $departement->id,
                        'nom' => $nomCommune,
                    ]);
                }
            }
        }
    }
}
