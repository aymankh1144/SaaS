<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * بيانات تجريبية للتطوير المحلي فقط.
     * ترفض العمل في الإنتاج لأن كلمة مرور المطعم التجريبي معروفة.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('تم تخطي البيانات التجريبية في بيئة الإنتاج.');

            return;
        }

        $restaurant = Restaurant::firstOrCreate(
            ['slug' => 'demo-restaurant'],
            [
                'name' => 'مطعم تجريبي',
                'admin_email' => 'demo@menusaas.local',
                'admin_username' => 'demo',
                'admin_password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        if ($restaurant->wasRecentlyCreated) {
            $restaurant->colorScheme()->create([
                'primary_color' => '#FF6B6B',
                'secondary_color' => '#4ECDC4',
                'accent_color' => '#FFE66D',
                'text_color' => '#333333',
                'border_color' => '#CCCCCC',
                'background_color' => '#FFFFFF',
            ]);

            $pizza = $restaurant->categories()->create(['name' => 'بيتزا', 'icon' => '🍕', 'sort_order' => 1]);
            $drinks = $restaurant->categories()->create(['name' => 'مشروبات', 'icon' => '🥤', 'sort_order' => 2]);

            $restaurant->dishes()->create([
                'category_id' => $pizza->id,
                'name' => 'بيتزا مرغريتا',
                'description' => 'جبنة موتزاريلا وصلصة طماطم',
                'price' => 45,
                'is_available' => true,
                'is_vegan' => false,
                'is_spicy' => false,
            ]);
            $restaurant->dishes()->create([
                'category_id' => $drinks->id,
                'name' => 'عصير ليمون بالنعنع',
                'price' => 12,
                'is_available' => true,
                'is_vegan' => true,
                'is_spicy' => false,
            ]);

            $restaurant->offers()->create([
                'title' => 'خصم الأسبوع',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'start_date' => now()->subDay(),
                'end_date' => now()->addMonth(),
            ]);
        }

        $this->command?->info('المطعم التجريبي: /restaurants/demo-restaurant/admin/login  (demo / password)');
    }
}
