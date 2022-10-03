<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeblocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codeblocks', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(false);
            $table->longText('codeblock')->nullable(true);
            $table->timestamps();
        });

        DB::table('codeblocks')->insert(
            [
                [
                    'type' => 'header',
                    'codeblock' => null,
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today()
                ],
                [
                    'type' => 'footer',
                    'codeblock' => null,
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today()
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codeblocks');
    }
}
