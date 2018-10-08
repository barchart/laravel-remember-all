<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRememberTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remember_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 100);
            $table->integer('user_id');
            $table->timestamps();
            $table->dateTime('expires_at');

            $table->unique(['token', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remember_tokens');
    }
}
