<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("address_line_1")->nullable();
            $table->string("address_line_2")->nullable();
            $table->string("address_city")->nullable();
            $table->string("address_region")->nullable();
            $table->string("address_postal_code")->nullable();
            $table->foreignId("address_country_id")->nullable();
            $table->string("main_contact_name")->nullable();
            $table->string("main_contact_telephone")->nullable();
            $table->string("main_contact_email")->nullable();
            $table->mediumText("description")->nullable();
            $table->string("coordinates")->nullable();
            $table->integer("distance")->nullable();
            $table->integer("travel_time_minutes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
};
