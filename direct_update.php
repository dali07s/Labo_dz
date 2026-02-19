<?php
$host = '127.0.0.1';
$db   = 'labo_dz';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // 1. Update Analyses
     $analyses = [
         'تحليل الدم الكامل (FNS)' => [
             'name_fr' => 'Numération Formule Sanguine (NFS/FNS)',
             'desc_fr' => 'Un examen de laboratoire qui fournit des informations détaillées sur les cellules du sang.',
             'prep_fr' => 'Généralement, aucun jeûne n\'est requis sauf indication contraire.',
             'dur_fr' => '24 heures'
         ],
         'تحليل السكر في الدم' => [
             'name_fr' => 'Glycémie (Sucre dans le sang)',
             'desc_fr' => 'Mesure le taux de glucose dans le sang pour dépister le diabète.',
             'prep_fr' => 'Jeûne de 8 à 12 heures requis.',
             'dur_fr' => 'Quelques heures'
         ],
         'تحليل الكوليسترول' => [
             'name_fr' => 'Bilan Lipidique (Cholestérol)',
             'desc_fr' => 'Mesure les différents types de graisses dans le sang.',
             'prep_fr' => 'Jeûne de 12 heures requis.',
             'dur_fr' => '24 heures'
         ]
     ];

     foreach ($analyses as $arName => $data) {
         $stmt = $pdo->prepare("UPDATE analyses SET name_fr = ?, description_fr = ?, preparation_instructions_fr = ?, duration_fr = ? WHERE name LIKE ?");
         $stmt->execute([$data['name_fr'], $data['desc_fr'], $data['prep_fr'], $data['dur_fr'], "%$arName%"]);
         echo "Updated Analysis: $arName\n";
     }

     // 2. Update Questions
     $questions = [
         'هل صمت لمدة 8 إلى 12 ساعة' => 'Avez-vous jeûné pendant 8 à 12 heures ?',
         'متى كانت آخر وجبة' => 'Quand avez-vous pris votre dernier repas ?',
         'هل كانت آخر وجبة غنية بالسكريات' => 'Votre dernier repas était-il riche en sucres ou en graisses ?',
         'هل شربت أي شيء خلال فترة الصيام' => 'Avez-vous bu quelque chose pendant la période de jeûne ?',
         'هل تناولت أي دواء' => 'Avez-vous pris des médicaments ce matin ?',
         'دواء للسكري' => 'S\'agit-il d\'un médicament contre le diabète ?',
         'هل لديك تشخيص مسبق بمرض السكري' => 'Avez-vous un diagnostic préalable de diabète ?',
         'الأعراض الحالية' => 'Symptômes actuels :',
         'نشاطاً بدنياً مكثفاً' => 'Activité physique intense ?',
         'هل دخنت خلال فترة الصيام' => 'Avez-vous fumé pendant la période de jeûne ?',
         'هل يوجد حمل' => 'Y a-t-il une grossesse ?'
     ];

     foreach ($questions as $arQ => $frQ) {
         $stmt = $pdo->prepare("UPDATE questions SET question_fr = ? WHERE question LIKE ?");
         $stmt->execute([$frQ, "%$arQ%"]);
         echo "Updated Question mapping: $arQ\n";
     }

     // 3. Update Options
     $options = [
         'نعم' => 'Oui',
         'لا' => 'Non',
         'أقل من 4 ساعات' => 'Moins de 4 heures',
         'أكثر من 12 ساعة' => 'Plus de 12 heures',
         '8 إلى 12 ساعة' => 'Entre 8 et 12 heures',
         'غير معروف' => 'Inconnu',
         'ماء فقط' => 'Eau uniquement'
     ];

     foreach ($options as $arO => $frO) {
         $stmt = $pdo->prepare("UPDATE options SET text_fr = ? WHERE text LIKE ?");
         $stmt->execute([$frO, "%$arO%"]);
         echo "Updated Option mapping: $arO\n";
     }

     echo "Direct Database Update Completed!\n";

} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
