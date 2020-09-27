<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\User;
        $admin->username = "admin";
        $admin->name     = "site admin";
        $admin->email    = "admin@gmail.com";
        $admin->password = \Hash::make("123456");
        $admin->roles    = json_encode(["ADMIN"]);        
        $admin->address  = "jl. giri makmur, lempake, samarinda utara";        
        $admin->phone    = "082350790354";
        $admin->avatar   = "tidak-ada-file.png";
        $admin->save();
        $this->command->info("user admin berhasil diinsert");
    }
}
