<?php

namespace Database\Seeders;

use App\Models\Analyse;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseTranslationSeeder extends Seeder
{
    public function run()
    {
        // 1. Translate Analyses (all real analyses, matched by code)
        $analysesByCode = [
            'CBC' => [
                'name_fr' => 'Hémogramme complet (NFS/CBC)',
                'description_fr' => 'Examen complet des composants du sang incluant les globules blancs, globules rouges, plaquettes et hémoglobine.',
                'prep_fr' => 'Aucun jeûne n’est généralement nécessaire, sauf indication contraire du médecin.',
                'dur_fr' => '24 heures',
            ],
            'FBS' => [
                'name_fr' => 'Glycémie à jeun',
                'description_fr' => 'Mesure le taux de glucose dans le sang pour dépister ou surveiller le diabète.',
                'prep_fr' => 'Jeûne de 8 à 12 heures requis. Seule l’eau est autorisée.',
                'dur_fr' => '2 à 4 heures',
            ],
            'LIPID' => [
                'name_fr' => 'Bilan lipidique (Cholestérol et triglycérides)',
                'description_fr' => 'Mesure les niveaux de cholestérol total, HDL, LDL et triglycérides dans le sang.',
                'prep_fr' => 'Jeûne de 12 heures requis. Éviter les repas riches en graisses la veille du prélèvement.',
                'dur_fr' => '24 heures',
            ],
            'LFT' => [
                'name_fr' => 'Bilan hépatique (Fonction du foie)',
                'description_fr' => 'Examen des enzymes hépatiques (ALT, AST), de la bilirubine et de l’albumine pour évaluer la fonction du foie.',
                'prep_fr' => 'Idéalement, jeûne de 8 heures. Éviter les médicaments pouvant influencer le foie après avis médical.',
                'dur_fr' => '24 à 48 heures',
            ],
            'RFT' => [
                'name_fr' => 'Bilan rénal (Fonction des reins)',
                'description_fr' => 'Mesure la créatinine, l’urée et l’acide urique pour évaluer la fonction rénale.',
                'prep_fr' => 'Jeûne de 8 heures recommandé. Boire suffisamment d’eau la veille de l’examen.',
                'dur_fr' => '24 heures',
            ],
            'URINE' => [
                'name_fr' => 'Examen cytobactériologique des urines (ECBU)',
                'description_fr' => 'Analyse complète de l’urine pour dépister les infections urinaires, le diabète ou certaines atteintes rénales.',
                'prep_fr' => 'Recueillir la première urine du matin après une toilette locale soigneuse.',
                'dur_fr' => '4 à 6 heures',
            ],
            'BLOOD_TYPE' => [
                'name_fr' => 'Groupage sanguin et facteur Rhésus',
                'description_fr' => 'Détermination du groupe sanguin (A, B, AB ou O) et du facteur Rhésus (positif ou négatif).',
                'prep_fr' => 'Aucune préparation particulière nécessaire. Le test peut être réalisé à tout moment.',
                'dur_fr' => '2 à 4 heures',
            ],
            'ESR' => [
                'name_fr' => 'Vitesse de sédimentation (VS)',
                'description_fr' => 'Mesure la vitesse de sédimentation des globules rouges pour rechercher une inflammation ou une maladie auto-immune.',
                'prep_fr' => 'Aucun jeûne requis. Le prélèvement peut être effectué à tout moment.',
                'dur_fr' => '2 à 4 heures',
            ],
            'THYROID' => [
                'name_fr' => 'Bilan thyroïdien (TSH, T3, T4)',
                'description_fr' => 'Évalue la fonction de la glande thyroïde à travers la mesure de TSH, T3 et T4.',
                'prep_fr' => 'Pas de jeûne obligatoire. Éviter de prendre le traitement thyroïdien dans les 4 heures précédant le prélèvement, après avis médical.',
                'dur_fr' => '24 à 48 heures',
            ],
            'VIT_D' => [
                'name_fr' => 'Dosage de la vitamine D',
                'description_fr' => 'Mesure le taux de vitamine D dans le sang pour dépister une carence ou un excès.',
                'prep_fr' => 'Aucune préparation spécifique. L’examen peut être réalisé à tout moment de la journée.',
                'dur_fr' => '48 à 72 heures',
            ],
        ];

        foreach (Analyse::all() as $analyse) {
            if (! isset($analysesByCode[$analyse->code])) {
                continue;
            }

            $frData = $analysesByCode[$analyse->code];

            $analyse->update([
                'name_fr' => $frData['name_fr'],
                'description_fr' => $frData['description_fr'],
                'preparation_instructions_fr' => $frData['prep_fr'],
                'duration_fr' => $frData['dur_fr'],
            ]);
        }

        // 2. Translate Questions (based on FnsQuestionsSeeder)
        $questions = [
            'هل صمت لمدة 8 إلى 12 ساعة قبل التحليل؟' => 'Avez-vous jeûné pendant 8 à 12 heures avant l\'analyse ?',
            'متى كانت آخر وجبة تناولتها؟' => 'Quand avez-vous pris votre dernier repas ?',
            'هل كانت آخر وجبة غنية بالسكريات أو الدهون؟' => 'Votre dernier repas était-il riche en sucres ou en graisses ?',
            'هل شربت أي شيء خلال فترة الصيام؟' => 'Avez-vous bu quelque chose pendant la période de jeûne ?',
            'هل تناولت أي دواء هذا الصباح؟' => 'Avez-vous pris des médicaments ce matin ?',
            'هل هو دواء للسكري' => 'S\'agit-il d\'un médicament contre le diabète (Insuline / Metformine) ?',
            'هل لديك تشخيص مسبق بمرض السكري؟' => 'Avez-vous un diagnostic préalable de diabète ?',
            'الأعراض الحالية' => 'Symptômes actuels (cochez tout ce qui s\'applique) :',
            'هل مارست نشاطاً بدنياً مكثفاً' => 'Avez-vous pratiqué une activité physique intense au cours des dernières 24 heures ?',
            'هل دخنت خلال فترة الصيام؟' => 'Avez-vous fumé pendant la période de jeûne ?',
            'هل يوجد حمل؟' => 'Y a-t-il une grossesse ?',
        ];

        foreach (Question::all() as $q) {
            $originalQ = $q->getRawOriginal('question');
            foreach ($questions as $arQ => $frQ) {
                if (stripos($originalQ, $arQ) !== false) {
                    $q->update(['question_fr' => $frQ]);
                    break;
                }
            }
        }

        // 3. Translate Options
        $options = [
            'نعم' => 'Oui',
            'لا' => 'Non',
            'أقل من 4 ساعات' => 'Moins de 4 heures',
            'بين 8 إلى 12 ساعة' => 'Entre 8 et 12 heures',
            'أكثر من 12 ساعة' => 'Plus de 12 heures',
            'غير معروف' => 'Inconnu',
            'ماء فقط' => 'Eau uniquement',
            'قهوة أو شاي بدون سكر' => 'Café ou thé sans sucre',
            'مشروب سكري أو حليب' => 'Boisson sucrée ou lait',
            'أول مرة' => 'Première fois',
            'عطش شديد' => 'Soif intense',
            'تبول متكرر' => 'Mictions fréquentes',
            'تعب غير عادي' => 'Fatigue inhabituelle',
            'لا يوجد' => 'Aucun',
            'غير قابل للتطبيق' => 'Non applicable',
        ];

        foreach (Option::all() as $o) {
            $originalO = $o->getRawOriginal('text');
            foreach ($options as $arO => $frO) {
                if (stripos($originalO, $arO) !== false) {
                    $o->update(['text_fr' => $frO]);
                    break;
                }
            }
        }
    }
}
