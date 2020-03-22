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
            $table->integer('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('occupation')->nullable();
            $table->string('other')->nullable();
            $table->string('report_type')->nullable();
            $table->enum('gender', \App\Models\CallReport::GENDER);
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
