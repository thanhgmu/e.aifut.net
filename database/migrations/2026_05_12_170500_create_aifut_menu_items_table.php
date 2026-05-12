<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aifut_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('source_system')->default('magicai');
            $table->string('actor_role')->default('user');
            $table->string('title');
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_enabled')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['actor_role', 'source_system', 'is_visible']);
            $table->index(['parent_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aifut_menu_items');
    }
};
