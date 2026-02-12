<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Analyse;
use Illuminate\Support\Facades\DB;

class RealAnalysesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing test data
        DB::table('analyses')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert 10 real medical analyses
        $analyses = [
            [
                'name' => 'تحليل الدم الشامل',
                'code' => 'CBC',
                'description' => 'فحص شامل لمكونات الدم يشمل عدد خلايا الدم البيضاء، الحمراء، الصفائح الدموية، والهيموغلوبين',
                'normal_range' => 'خلايا الدم البيضاء: 4-11 × 10³/μL، خلايا الدم الحمراء: 4.5-6 مليون/μL، الهيموغلوبين: 13-18 g/dL',
                'price' => 800.00,
                'duration' => '24 ساعة',
                'preparation_instructions' => 'لا يتطلب صيام. يمكن إجراء التحليل في أي وقت.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل السكر في الدم',
                'code' => 'FBS',
                'description' => 'قياس مستوى الجلوكوز في الدم للكشف عن مرض السكري أو متابعة العلاج',
                'normal_range' => 'صائم: 70-100 mg/dL، عشوائي: أقل من 140 mg/dL',
                'price' => 500.00,
                'duration' => '2-4 ساعات',
                'preparation_instructions' => 'يتطلب الصيام لمدة 8-12 ساعة قبل التحليل. يُسمح بشرب الماء فقط.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل الكوليسترول والدهون',
                'code' => 'LIPID',
                'description' => 'فحص شامل لمستويات الكوليسترول والدهون الثلاثية في الدم',
                'normal_range' => 'الكوليسترول الكلي: أقل من 200 mg/dL، HDL: أكثر من 40 mg/dL، LDL: أقل من 130 mg/dL',
                'price' => 1200.00,
                'duration' => '24 ساعة',
                'preparation_instructions' => 'يتطلب الصيام لمدة 12 ساعة قبل التحليل. تجنب الأطعمة الدسمة في اليوم السابق.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل وظائف الكبد',
                'code' => 'LFT',
                'description' => 'فحص شامل لوظائف الكبد يشمل إنزيمات ALT، AST، البيليروبين، والألبومين',
                'normal_range' => 'ALT: 7-56 U/L، AST: 10-40 U/L، البيليروبين: 0.1-1.2 mg/dL',
                'price' => 1500.00,
                'duration' => '24-48 ساعة',
                'preparation_instructions' => 'يفضل الصيام لمدة 8 ساعات. تجنب الأدوية التي قد تؤثر على الكبد قبل الفحص بعد استشارة الطبيب.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل وظائف الكلى',
                'code' => 'RFT',
                'description' => 'فحص شامل لوظائف الكلى يشمل الكرياتينين، اليوريا، وحمض اليوريك',
                'normal_range' => 'الكرياتينين: 0.7-1.3 mg/dL، اليوريا: 7-20 mg/dL، حمض اليوريك: 3.5-7.2 mg/dL',
                'price' => 1200.00,
                'duration' => '24 ساعة',
                'preparation_instructions' => 'يفضل الصيام لمدة 8 ساعات. شرب كمية كافية من الماء في اليوم السابق.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل البول الكامل',
                'code' => 'URINE',
                'description' => 'فحص شامل للبول للكشف عن التهابات المسالك البولية، السكري، أو مشاكل الكلى',
                'normal_range' => 'لون: أصفر فاتح، الكثافة: 1.005-1.030، البروتين: سلبي، الجلوكوز: سلبي',
                'price' => 600.00,
                'duration' => '4-6 ساعات',
                'preparation_instructions' => 'جمع عينة البول الصباحي الأول. غسل المنطقة التناسلية قبل جمع العينة.',
                'availability' => true,
            ],
            [
                'name' => 'فصيلة الدم',
                'code' => 'BLOOD_TYPE',
                'description' => 'تحديد فصيلة الدم ونوع العامل الريصي (Rh)',
                'normal_range' => 'A, B, AB, أو O مع موجب أو سالب',
                'price' => 700.00,
                'duration' => '2-4 ساعات',
                'preparation_instructions' => 'لا يتطلب أي تحضيرات خاصة. يمكن إجراء التحليل في أي وقت.',
                'availability' => true,
            ],
            [
                'name' => 'سرعة الترسيب',
                'code' => 'ESR',
                'description' => 'قياس سرعة ترسيب كريات الدم الحمراء للكشف عن الالتهابات والأمراض المناعية',
                'normal_range' => 'الرجال: 0-15 mm/hr، النساء: 0-20 mm/hr',
                'price' => 400.00,
                'duration' => '2-4 ساعات',
                'preparation_instructions' => 'لا يتطلب صيام. يمكن إجراء التحليل في أي وقت.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل وظائف الغدة الدرقية',
                'code' => 'THYROID',
                'description' => 'فحص شامل لوظائف الغدة الدرقية يشمل TSH، T3، T4',
                'normal_range' => 'TSH: 0.4-4.0 mIU/L، T3: 80-200 ng/dL، T4: 5-12 μg/dL',
                'price' => 2000.00,
                'duration' => '24-48 ساعة',
                'preparation_instructions' => 'لا يتطلب صيام. تجنب أدوية الغدة الدرقية قبل 4 ساعات من التحليل بعد استشارة الطبيب.',
                'availability' => true,
            ],
            [
                'name' => 'تحليل فيتامين د',
                'code' => 'VIT_D',
                'description' => 'قياس مستوى فيتامين د في الدم للكشف عن النقص أو الزيادة',
                'normal_range' => 'كافٍ: 30-100 ng/mL، نقص: أقل من 20 ng/mL، نقص حاد: أقل من 10 ng/mL',
                'price' => 2500.00,
                'duration' => '48-72 ساعة',
                'preparation_instructions' => 'لا يتطلب صيام. يمكن إجراء التحليل في أي وقت من اليوم.',
                'availability' => true,
            ],
        ];

        foreach ($analyses as $analysis) {
            Analyse::create($analysis);
        }

        $this->command->info('تم إضافة 10 تحاليل طبية حقيقية بنجاح!');
    }
}
