<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamp('start_datetime')->nullable(true);
            $table->timestamp('end_datetime')->nullable(true);
            $table->longText('description')->nullable(true);
            $table->string('category')->nullable(true);
            $table->integer('vote_schedule')->default(12);
            $table->string('popular_tag')->default(false);
            $table->integer('captcha_type')->default('1');
            $table->integer('option_select')->default('0');
            $table->string('feature_image')->nullable(true);
            $table->softDeletes();
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
        Schema::dropIfExists('polls');
    }
}
