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
            array('id'=>1, 'name'=>'Fever', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>2, 'name'=>'Cough', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>3, 'name'=>'Headache', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>4, 'name'=>'Runny Nose', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>5, 'name'=>'Breathing Difficulty', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>6, 'name'=>'Body Pain', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('id'=>7, 'name'=>'Unwellness Feeling', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
        );
        \App\Models\CallRumorType::insert($callRumorTypes);
    }
}
