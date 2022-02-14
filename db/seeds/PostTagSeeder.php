<?php


use Phinx\Seed\AbstractSeed;

class PostTagSeeder extends AbstractSeed
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
                'post_id'    => $faker->numberBetween(1, 5),
                'tag_id'     => $faker->numberBetween(1, 25),
            ];
        }

        $this->table('post_tag')->insert($data)->saveData();
    }

    public function getDependencies()
    {
        return [
            'PostSeeder',
            'TagSeeder'
        ];
    }
    
}
