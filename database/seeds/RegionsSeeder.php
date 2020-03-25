<?php

use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = array(
            array('id'=>1, 'name'=>'Addis Ababa City Administration', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>2, 'name'=>'Dire Dawa City Administration', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>3, 'name'=>'Tigray Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>4, 'name'=>'Afar Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>5, 'name'=>'Amhara Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>6, 'name'=>'Oromia Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>7, 'name'=>'Somali Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>8, 'name'=>'Benishangul - Gumuz Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>9, 'name'=>'SNNP Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>10, 'name'=>'Harari Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>11, 'name'=>'Gambella Regional State', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),

        );
        \App\Models\Region::insert($regions);
    }
}
