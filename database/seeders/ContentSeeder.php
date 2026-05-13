<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // Général (toutes semaines)
            ['title' => 'Les bases d\'une alimentation saine pendant la grossesse', 'description' => 'Découvrez les nutriments essentiels pour vous et votre bébé.', 'type' => 'article', 'url' => 'https://example.com', 'week' => null],
            ['title' => 'Yoga prénatal — séance douce pour toutes les semaines',     'description' => 'Une séance guidée adaptée à chaque trimestre.', 'type' => 'video', 'url' => 'https://youtube.com', 'week' => null],

            // Semaine 8
            ['title' => 'Semaine 8 — votre bébé a la taille d\'une framboise',       'description' => 'Découvrez le développement de votre bébé cette semaine.', 'type' => 'article', 'url' => 'https://example.com', 'week' => 8],
            ['title' => 'Gérer les nausées matinales — conseils pratiques',           'description' => 'Des astuces simples pour mieux vivre les nausées du 1er trimestre.', 'type' => 'audio', 'url' => 'https://example.com', 'week' => 8],

            // Semaine 12
            ['title' => 'Semaine 12 — fin du 1er trimestre, ce qui change',          'description' => 'Le risque de fausse couche diminue, votre corps se transforme.', 'type' => 'article', 'url' => 'https://example.com', 'week' => 12],
            ['title' => 'L\'échographie du 1er trimestre — à quoi s\'attendre',      'description' => 'Tout ce que vous verrez lors de votre première échographie.', 'type' => 'video', 'url' => 'https://youtube.com', 'week' => 12],
            ['title' => 'Méditation pour futures mamans — 10 minutes de calme',      'description' => 'Une méditation guidée pour apaiser le stress du premier trimestre.', 'type' => 'audio', 'url' => 'https://example.com', 'week' => 12],

            // Semaine 20
            ['title' => 'L\'échographie morphologique — tout comprendre',             'description' => 'Ce que les médecins recherchent lors de cette échographie clé.', 'type' => 'article', 'url' => 'https://example.com', 'week' => 20],
            ['title' => 'Sentir les premiers mouvements de bébé',                    'description' => 'Quand et comment sentir les premières petites coups de pied.', 'type' => 'video', 'url' => 'https://youtube.com', 'week' => 20],

            // Semaine 32
            ['title' => 'Préparer sa valise de maternité — liste complète',          'description' => 'Tout ce qu\'il faut prévoir pour le jour J.', 'type' => 'article', 'url' => 'https://example.com', 'week' => 32],
            ['title' => 'Respiration et sophrologie pour l\'accouchement',            'description' => 'Des techniques de respiration pour gérer la douleur naturellement.', 'type' => 'audio', 'url' => 'https://example.com', 'week' => 32],
        ];

        foreach ($contents as $content) {
            Content::create($content);
        }
    }
}