<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignedCallReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_call_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('call_report_id');
            $table->unsignedBigInteger('contact_group_id');
            $table->unsignedBigInteger('created_by');
            $table->enum('assignment_type', \App\Models\AssignedCallReport::ASSIGNMENT_TYPE);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('call_report_id')->references('id')->on('call_reports')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('contact_group_id')->references('id')->on('contact_groups')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('assigned_call_reports');
    }
}
