<?php

use Illuminate\Support\Facades\App;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFakeChattableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::runningUnitTests()) {
            Schema::create('chattable', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (App::runningUnitTests()) {
            Schema::dropIfExists('chattable');
        }
    }
}
