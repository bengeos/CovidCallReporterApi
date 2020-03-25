<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('wereda_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('sub_city_id')->nullable();
            $table->unsignedBigInteger('kebele_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('full_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('second_phone')->nullable();
            $table->string('occupation')->nullable();
            $table->string('callerType')->nullable();
            $table->string('other')->nullable();
            $table->string('report_type')->nullable();
            $table->integer('report_group_id')->default(0);
            $table->longText('description')->nullable();
            $table->longText('remark_1')->nullable();
            $table->longText('remark_2')->nullable();
            $table->enum('gender', \App\Models\CallReport::GENDER);
            $table->boolean('is_travel_hx')->default(false);
            $table->boolean('is_contacted_with_pt')->default(false);
            $table->boolean('is_visited_animal')->default(false);
            $table->boolean('is_visited_hf')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('wereda_id')->references('id')->on('weredas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('sub_city_id')->references('id')->on('sub_cities')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_reports');
    }
}
