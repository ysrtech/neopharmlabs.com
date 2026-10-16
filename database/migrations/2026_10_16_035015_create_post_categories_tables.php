<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('post_category_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'post_category');
            $table->string('title', 200)->nullable();
            $table->string('heading', 255)->nullable();
        });

        Schema::create('post_category_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'post_category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_category_translations');
        Schema::dropIfExists('post_category_slugs');
        Schema::dropIfExists('post_categories');
    }
};
