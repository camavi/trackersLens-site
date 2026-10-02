<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_artifacts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kind');
            $table->timestamps();
        });
        Schema::create('catalog_releases', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('artifact_id')->constrained('catalog_artifacts')->cascadeOnDelete();
            $table->string('version');
            $table->string('title');
            $table->text('description');
            $table->string('license');
            $table->string('visibility');
            $table->string('sha256', 64);
            $table->longText('bundle_json');
            $table->timestamps();
            $table->unique(['artifact_id', 'version']);
            $table->index('visibility');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_releases');
        Schema::dropIfExists('catalog_artifacts');
    }
};
