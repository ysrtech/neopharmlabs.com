<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->foreignId('post_category_id')->nullable();
            $table->date('published_at')->nullable();
            $table->string('author_name', 150)->nullable();
            $table->boolean('legacy')->default(false);
        });

        Schema::create('post_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'post');
            $table->string('title', 255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('author_bio')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
        });

        Schema::create('post_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'post');
        });

        Schema::create('post_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'post');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_revisions');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('post_slugs');
        Schema::dropIfExists('posts');
    }
};
