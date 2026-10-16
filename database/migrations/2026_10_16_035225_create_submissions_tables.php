<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('title', 255)->nullable();
            $table->string('form', 50)->index();
            $table->string('locale', 5)->default('en');
            $table->string('email', 255)->nullable();
            $table->string('subject_type', 50)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('data')->nullable();
            $table->json('attachments')->nullable();
            $table->string('ip', 45)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};
