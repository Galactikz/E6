<?php

namespace Database\Seeders;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateTask;
use Illuminate\Database\Seeder;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Mariage en 6 mois',
                'slug' => 'mariage-6-mois',
                'description' => 'Planification intensive pour un mariage dans 6 mois',
                'months_before' => 6,
                'tasks' => [
                    ['title' => 'Définir le budget total', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Budget'],
                    ['title' => 'Choisir la date', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Organisation'],
                    ['title' => 'Réserver la salle', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Choisir le traiteur', 'months_before_wedding' => 5, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver le photographe', 'months_before_wedding' => 5, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Envoyer les faire-part', 'months_before_wedding' => 4, 'priority' => 'high', 'category' => 'Invités'],
                    ['title' => 'Choisir la robe', 'months_before_wedding' => 4, 'priority' => 'high', 'category' => 'Tenue'],
                    ['title' => 'Choisir le costume', 'months_before_wedding' => 4, 'priority' => 'medium', 'category' => 'Tenue'],
                    ['title' => 'Réserver le DJ', 'months_before_wedding' => 4, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Confirmer le plan de table', 'months_before_wedding' => 1, 'priority' => 'high', 'category' => 'Organisation'],
                ],
            ],
            [
                'name' => 'Mariage en 12 mois',
                'slug' => 'mariage-12-mois',
                'description' => 'Planning idéal pour un mariage dans 12 mois',
                'months_before' => 12,
                'tasks' => [
                    ['title' => 'Définir le budget total', 'months_before_wedding' => 12, 'priority' => 'high', 'category' => 'Budget'],
                    ['title' => 'Choisir la date', 'months_before_wedding' => 12, 'priority' => 'high', 'category' => 'Organisation'],
                    ['title' => 'Visiter des salles de réception', 'months_before_wedding' => 11, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver la salle', 'months_before_wedding' => 10, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Choisir le traiteur', 'months_before_wedding' => 9, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver le photographe', 'months_before_wedding' => 9, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver le vidéaste', 'months_before_wedding' => 8, 'priority' => 'medium', 'category' => 'Prestataires'],
                    ['title' => 'Envoyer les save the date', 'months_before_wedding' => 8, 'priority' => 'medium', 'category' => 'Invités'],
                    ['title' => 'Choisir la robe', 'months_before_wedding' => 7, 'priority' => 'high', 'category' => 'Tenue'],
                    ['title' => 'Envoyer les faire-part', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Invités'],
                    ['title' => 'Réserver le DJ', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Planifier la lune de miel', 'months_before_wedding' => 5, 'priority' => 'medium', 'category' => 'Organisation'],
                    ['title' => 'Essayage robe finale', 'months_before_wedding' => 2, 'priority' => 'high', 'category' => 'Tenue'],
                    ['title' => 'Confirmer tous les prestataires', 'months_before_wedding' => 1, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Confirmer le plan de table', 'months_before_wedding' => 1, 'priority' => 'high', 'category' => 'Organisation'],
                ],
            ],
            [
                'name' => 'Mariage en 18 mois',
                'slug' => 'mariage-18-mois',
                'description' => 'Planification sereine pour un mariage dans 18 mois',
                'months_before' => 18,
                'tasks' => [
                    ['title' => 'Définir le budget total', 'months_before_wedding' => 18, 'priority' => 'high', 'category' => 'Budget'],
                    ['title' => 'Choisir la date', 'months_before_wedding' => 18, 'priority' => 'high', 'category' => 'Organisation'],
                    ['title' => 'Définir le style du mariage', 'months_before_wedding' => 17, 'priority' => 'medium', 'category' => 'Organisation'],
                    ['title' => 'Visiter des salles de réception', 'months_before_wedding' => 16, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver la salle', 'months_before_wedding' => 15, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Rechercher un photographe', 'months_before_wedding' => 14, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Réserver le photographe', 'months_before_wedding' => 13, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Choisir le traiteur', 'months_before_wedding' => 12, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Envoyer les save the date', 'months_before_wedding' => 10, 'priority' => 'medium', 'category' => 'Invités'],
                    ['title' => 'Choisir la robe', 'months_before_wedding' => 9, 'priority' => 'high', 'category' => 'Tenue'],
                    ['title' => 'Réserver le DJ', 'months_before_wedding' => 8, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Envoyer les faire-part', 'months_before_wedding' => 6, 'priority' => 'high', 'category' => 'Invités'],
                    ['title' => 'Planifier la lune de miel', 'months_before_wedding' => 6, 'priority' => 'medium', 'category' => 'Organisation'],
                    ['title' => 'Organiser l\'enterrement de vie', 'months_before_wedding' => 3, 'priority' => 'low', 'category' => 'Fête'],
                    ['title' => 'Essayage robe finale', 'months_before_wedding' => 2, 'priority' => 'high', 'category' => 'Tenue'],
                    ['title' => 'Confirmer tous les prestataires', 'months_before_wedding' => 1, 'priority' => 'high', 'category' => 'Prestataires'],
                    ['title' => 'Confirmer le plan de table', 'months_before_wedding' => 1, 'priority' => 'high', 'category' => 'Organisation'],
                    ['title' => 'Préparer les enveloppes remerciements', 'months_before_wedding' => 1, 'priority' => 'low', 'category' => 'Organisation'],
                ],
            ],
        ];

        foreach ($templates as $templateData) {
            $tasks = $templateData['tasks'];
            unset($templateData['tasks']);

            $template = ChecklistTemplate::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );

            foreach ($tasks as $i => $task) {
                ChecklistTemplateTask::updateOrCreate(
                    ['checklist_template_id' => $template->id, 'title' => $task['title']],
                    array_merge($task, [
                        'checklist_template_id' => $template->id,
                        'sort_order' => $i + 1,
                    ])
                );
            }
        }
    }
}
