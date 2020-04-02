<?php

use Illuminate\Database\Seeder;

class SymptomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $symptoms = array(
            array('id' => 1, 'name' => 'Runny Nose', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 2, 'name' => 'Sore Throat', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id' => 3, 'name' => 'Shortness Of Breath', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
        );
        \App\Models\SymptomType::insert($symptoms);
    }
}
