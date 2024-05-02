<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_room_checkable_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checkable_id');
            $table->string('checkable_type');
            $table->boolean('checked')->default(false);
            $table->timestamps();


            $table->index(['checkable_id', 'checkable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chattable_check_statuses');
    }
};
