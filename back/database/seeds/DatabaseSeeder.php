<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
<<<<<<< HEAD
     * Run the database seeds.
=======
     * Seed the application's database.
>>>>>>> c5099344416609b3c15e407a399ac3daa56e5c6f
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	'name' => 'Testeur',
        ]);

        DB::table('users')->insert([
        	'first_name' => str_random(8),
        	'last_name' => str_random(8),
        	'email'=> str_random(8).'@yopmail.com',
        	'encrypted_password' => bcrypt('secret'),
        	'role_id' => 1,
        	'rgpd_accepted' => true,
        ]);
    }
}
