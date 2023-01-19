<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('profile_img');
            $table->string('instagram_url');
            $table->text('facebook_url');
            $table->text('twitter_url');
            $table->text('services');
            $table->text('about_us');
            $table->text('working_time');
            $table->text('health_care');
            $table->text('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('specialties_id');
            $table->string('password');
            $table->integer('type');
            $table->string('mobile_number');
            $table->string('reference_code');
            $table->integer('total_points');
            $table->text('device_token');
            $table->integer('status');
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
        Schema::dropIfExists('doctors');
    }
}
