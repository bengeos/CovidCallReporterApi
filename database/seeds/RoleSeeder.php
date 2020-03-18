<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array('id' => 1, 'name' => 'Super Admin', 'description' => 'Default Super Admin', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 2, 'name' => 'Syste Admin', 'description' => 'Default Super Viewer', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
        );
        Role::insert($roles);
    }
}
