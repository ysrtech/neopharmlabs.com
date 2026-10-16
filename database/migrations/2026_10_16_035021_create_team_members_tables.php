<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('team_member_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'team_member');
            $table->string('name', 200)->nullable();
            $table->string('role', 255)->nullable();
            $table->text('lead')->nullable();
            $table->text('bio')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('team_member_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'team_member');
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_member_translations');
        Schema::dropIfExists('team_member_slugs');
        Schema::dropIfExists('team_members');
    }
};
