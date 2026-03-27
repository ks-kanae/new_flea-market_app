<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'テスト出品者',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
            'profile_completed' => true,
        ]);

        $user->profile()->create([
            'name' => 'テスト出品者',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);
    }
}
