<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
