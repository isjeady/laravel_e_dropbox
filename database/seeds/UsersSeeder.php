<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //php artisan db:seed --class=UsersSeeder
    public function run()
    {
		$user = new User();
		$user->email = "admin@admin.com";
		$user->password = bcrypt('adminadmin');
		$user->name = "Admin";
        $user->save();
        $this->command->info('Admin Created !!!');
    }
}
