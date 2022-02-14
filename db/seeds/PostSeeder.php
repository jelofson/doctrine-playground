<?php


use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'title'        => $faker->sentence(),
                'post'        => $faker->paragraph(),
                'user_id'     => $faker->randomDigitNotNull(),
                'created'     => $faker->date . ' ' . $faker->time(),
                'updated'     => $faker->date . ' ' . $faker->time(),
            ];
        }

        $this->table('post')->insert($data)->saveData();
    }

    public function getDependencies()
    {
        return [
            'UserSeeder'
        ];
    }
}
