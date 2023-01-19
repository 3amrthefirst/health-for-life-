<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->text('firebase_id');
            $table->string('fullname');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('user_name');
            $table->string('email');
            $table->string('profile_img');
            $table->string('instagram_url');
            $table->string('facebook_url');
            $table->string('twitter_url');
            $table->text('biodata');
            $table->string('password');
            $table->integer('type');
            $table->string('mobile_number');
            $table->text('location');
            $table->string('insurance_company_id');
            $table->string('insurance_no');
            $table->string('insurance_card_pic');
            $table->text('allergies_to_medicine');
            $table->string('current_height');
            $table->string('current_weight');
            $table->string('BMI');
            $table->string('reference_code');
            $table->integer('total_points');
            $table->text('device_token');
            $table->date('date');
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
        Schema::dropIfExists('patients');
    }
}
