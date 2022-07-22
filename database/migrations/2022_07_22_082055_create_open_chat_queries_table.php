<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenChatQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_chat_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained();
            $table->string('sender_id');
            $table->string('sender_email');
            $table->text('query');
            $table->boolean('is_attended')->default(0);
            $table->string('attended_by')->nullable();
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
        Schema::dropIfExists('open_chat_queries');
    }
}
