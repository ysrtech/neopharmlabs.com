<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('title', 255)->nullable();
            $table->string('from_path', 500)->unique();
            $table->string('to_path', 500);
            $table->unsignedSmallInteger('status_code')->default(301);
        });
    }

    public function down()
    {
        Schema::dropIfExists('redirects');
    }
};
