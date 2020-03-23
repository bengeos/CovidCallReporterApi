<?php

use Illuminate\Database\Seeder;

class CallRumorTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $callRumorTypes = array(
            array('id'=>1, 'name'=>'Fever'),
            array('id'=>2, 'name'=>'Cough'),
            array('id'=>3, 'name'=>'Headache'),
            array('id'=>4, 'name'=>'Runny Nose'),
            array('id'=>5, 'name'=>'Breathing Difficulty'),
            array('id'=>6, 'name'=>'Body Pain'),
            array('id'=>7, 'name'=>'Unwellness Feeling'),
        );
        \App\Models\CallRumorType::insert($callRumorTypes);
    }
}
