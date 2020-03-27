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
            array('id' => 2, 'name' => 'Call Center Admin', 'description' => 'Default Call Center Admin', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 3, 'name' => 'Call Reporter', 'description' => 'Default Call Reporter', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 4, 'name' => 'Task Force', 'description' => 'Default Task Force', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 5, 'name' => 'Rapid Response', 'description' => 'Default Rapid Response', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
        );
        Role::insert($roles);
    }
}
