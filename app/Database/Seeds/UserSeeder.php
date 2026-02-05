<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder{
    public function run (){
        $faker = getFaker();
        for($i=0; $i<10; $i++){
            $data = array(
                'username' => $faker->userName(),
                'email'    => $faker->unique()->safeEmail(),
                'password' => password_hash('password123', PASSWORD_BCRYPT)
            );

            $this->db->table('users')->insert($data);
        }
        $data = array(
            'username' => 'test',
            'email'    => 'test@gmail.com',
            'password' => '12345test'
        );

        $this->db->table('users')->insert($data);
    }

}