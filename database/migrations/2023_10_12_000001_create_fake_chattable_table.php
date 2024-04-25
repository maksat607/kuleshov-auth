<?php

use Illuminate\Support\Facades\App;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::environment(['local'])) {
            Schema::create('application', function (Blueprint $table) {
                $table->id();
                $table->string('title');
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
        if (!App::environment(['local'])) {
            Schema::dropIfExists('application');
        }
    }
}
