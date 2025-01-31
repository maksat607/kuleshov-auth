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
        Schema::table('chat_room_messages', function (Blueprint $table) {
            $table->index(['chat_room_id', 'created_at'], 'idx_room_created');
        });

        Schema::table('chat_room_message_read_statuses', function (Blueprint $table) {
            $table->index(['chat_room_message_id', 'read_status'], 'idx_message_status');
        });

        Schema::table('chat_room_participants', function (Blueprint $table) {
            $table->index(['user_id', 'chat_room_id', 'id'], 'idx_user_room_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_room_messages', function (Blueprint $table) {
            $table->dropIndex('idx_room_created');
        });

        Schema::table('chat_room_message_read_statuses', function (Blueprint $table) {
            $table->dropIndex('idx_message_status');
        });

        Schema::table('chat_room_participants', function (Blueprint $table) {
            $table->dropIndex('idx_user_room_lookup');
        });
    }
};
