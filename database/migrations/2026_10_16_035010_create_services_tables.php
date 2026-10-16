<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->integer('position')->unsigned()->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->text('icon_svg')->nullable();
            $table->json('taxonomy')->nullable();
        });

        Schema::create('service_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'service');
            $table->string('title', 200)->nullable();
            $table->string('hero_title', 255)->nullable();
            $table->text('hero_intro')->nullable();
            $table->string('hero_button_label', 100)->nullable();
            $table->string('hero_button_url', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('service_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'service');
        });

        Schema::create('service_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'service');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_revisions');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('service_slugs');
        Schema::dropIfExists('services');
    }
};
