<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('zh_CN');
        for ($x = 0; $x <env('DB_SEED_NUM'); $x++) {
            \App\Models\users::create([
                'user_name' => \Faker\Provider\Uuid::uuid().'_'.$faker->word,
                'department_name' => $faker->firstNameMale .'_'.\Faker\Provider\Uuid::uuid().'_'.$faker->firstNameMale. '单位',
                'passwd' => bcrypt('112233'),
                'is_admin' => 0,
            ]);
            \App\Models\notices::create([
                'title' => $faker->words(3, true),
                'content' => $faker->realText(),
            ]);
        }
        for ($x = 0; $x < env('DB_SEED_NUM'); $x++) {
            \App\Models\login_records::create([
                'name' => \Faker\Provider\Uuid::uuid().'_'.$faker->firstNameMale,
                'phone_munber' => $faker->phoneNumber,
                'user_id' => rand(1, env('DB_SEED_NUM')),
            ]);
            \App\Models\chat_records::create([
                'from_user_id' => rand(1, env('DB_SEED_NUM')),
                'from_user_name' => $faker->phoneNumber,
                'to_user_id' => rand(1, env('DB_SEED_NUM')),
                'to_user_name' => $faker->phoneNumber,
                'content' => $faker->text(200),
                'attachment_id' => rand(1, env('DB_SEED_NUM')),
            ]);
            \App\Models\attachments::create([
                'file_path' => $faker->imageUrl(),
                'user_id' => rand(1, env('DB_SEED_NUM')),
                'update_user_name' => \Faker\Provider\Uuid::uuid().'_'.$faker->firstNameMale,
            ]);
            \App\Models\notice_relations::create([
                'notice_id' => rand(1, env('DB_SEED_NUM')),
                'user_id' => rand(1, env('DB_SEED_NUM')),
            ]);
            \App\Models\punch_time_records::create([
                'name' => \Faker\Provider\Uuid::uuid().'_'.$faker->firstNameMale,
                'user_id' => rand(1, env('DB_SEED_NUM')),
                'required_time' => $faker->date("Y-m-d H:i:s", 'now'),
                'actual_time' => $faker->date("Y-m-d H:i:s", 'now'),
            ]);
            \App\Models\punch_time_settings::create([
                'clock_time' => $faker->date("H:i:s", 'now'),
                'unable_at' => $faker->date("Y-m-d H:i:s", 'now'),
            ]);
        }
        for ($x = 0; $x < 10; $x++) {
            \App\Models\punch_time_settings::create([
                'clock_time' => $faker->date("H:i:s", 'now'),
            ]);
            \App\Models\punch_time_records::create([
                'name' => \Faker\Provider\Uuid::uuid().'_'.$faker->firstNameMale,
                'user_id' => rand(1, env('DB_SEED_NUM')),
                'required_time' => $faker->date("Y-m-d H:i:s", 'now'),
            ]);
        }
    }
}
