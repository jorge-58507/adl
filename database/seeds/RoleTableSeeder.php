<?php

use Illuminate\Database\Seeder;
use App\role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new role();
        $role->name = "admin";
        $role->description = "Administrador";
        $role->save();

        $role = new role();
        $role->name = "user";
        $role->description = "Usuario";
        $role->save();
    }
}
