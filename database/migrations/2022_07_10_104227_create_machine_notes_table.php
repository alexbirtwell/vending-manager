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
        Schema::create('machine_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->index();
            $table->foreignId('user_id')->index();
            $table->mediumText('note')->nullable();
            $table->string('note_type')->index()->default('admin'); //or customer
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
        Schema::dropIfExists('machine_notes');
    }
};
