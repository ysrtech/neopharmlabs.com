<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('type', 30)->default('white-papers');
            $table->date('published_at')->nullable();
        });

        Schema::create('resource_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'resource');
            $table->string('title', 255)->nullable();
            $table->text('intro')->nullable();
            $table->text('body')->nullable();
            $table->string('button_label', 100)->nullable();
            $table->string('form_title', 255)->nullable();
            $table->string('presenters_title', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('resource_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'resource');
        });

        Schema::create('resource_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'resource');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_revisions');
        Schema::dropIfExists('resource_translations');
        Schema::dropIfExists('resource_slugs');
        Schema::dropIfExists('resources');
    }
};
