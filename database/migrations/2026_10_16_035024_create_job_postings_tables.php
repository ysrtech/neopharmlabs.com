<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('reference', 50)->nullable()->index();
            $table->string('department', 150)->nullable();
            $table->string('location', 150)->nullable();
            $table->string('schedule', 100)->nullable();
            $table->date('posted_at')->nullable();
        });

        Schema::create('job_posting_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'job_posting');
            $table->string('title', 255)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('body')->nullable();
            $table->text('requirements')->nullable();
        });

        Schema::create('job_posting_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'job_posting');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_posting_translations');
        Schema::dropIfExists('job_posting_slugs');
        Schema::dropIfExists('job_postings');
    }
};
