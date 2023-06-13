<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->uuid('id');
            $table->text('title');
            $table->longText('description')->nullable();
            $table->string('slug', 200)->nullable();
            $table->string('salary', 200)->nullable();
            $table->longText('benefits')->nullable();
            $table->string('city', 200)->nullable();
            $table->text('feature_image')->nullable();
            $table->timestamp('due_date')->nullable();
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
        Schema::dropIfExists('careers');
    }
};
