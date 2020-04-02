<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallReportFollowupSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_report_followup_symptoms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('call_report_followup_id');
            $table->unsignedBigInteger('symptom_type_id');
            $table->timestamps();
            $table->foreign('call_report_followup_id')->references('id')->on('call_report_followups')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('symptom_type_id')->references('id')->on('symptom_types')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_report_followup_symptoms');
    }
}
