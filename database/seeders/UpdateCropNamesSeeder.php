<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCropNamesSeeder extends Seeder
{
    public function run()
    {
        $translations = [
            'موز' => 'Banana',
            'برتقال' => 'Orange',
            'بندورة' => 'Tomato',
            'خيار' => 'Cucumber',
            'طماطم' => 'Tomato',
            'فلفل' => 'Pepper',
            'باذنجان' => 'Eggplant',
            'كوسة' => 'Zucchini',
        ];

        foreach ($translations as $arabic => $english) {
            DB::table('crops')
                ->where('name', $arabic)
                ->update(['name_en' => $english]);
        }
    }
}
