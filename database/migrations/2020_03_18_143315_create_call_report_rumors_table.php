<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallReportRumorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_report_rumors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('call_report_id');
            $table->unsignedBigInteger('call_rumor_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('call_report_id')->references('id')->on('call_reports')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('call_rumor_type_id')->references('id')->on('call_rumor_types')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_report_rumors');
    }
}
