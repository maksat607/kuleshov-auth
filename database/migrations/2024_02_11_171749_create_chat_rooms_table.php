<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            if (App::runningUnitTests()) {
                $table->foreignId('sender_id')->references('id')->on('fake_users')->cascadeOnDelete();
            }else{
                $table->foreignId('sender_id')->references('id')->on('users')->cascadeOnDelete();
            }


            $table->unsignedBigInteger('chattable_id')->nullable();
            $table->string('chattable_type')->nullable();
            $table->index(['chattable_id', 'chattable_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
