<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_appointment', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->date('date');
            $table->integer('test_appointment_slots_id');
            $table->string('startTime');
            $table->string('endTime');
            $table->text('description');
            $table->text('symptoms');
            $table->text('medicines_taken');
            $table->text('insurance_details');            
            $table->integer('status')->default(1)->comment('1- pending, 2- approved, 3- reject, 4-absent');
            $table->string('mobile_number');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_appointment');
    }
}
