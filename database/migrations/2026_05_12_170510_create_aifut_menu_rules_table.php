<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aifut_menu_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('aifut_menu_items')->cascadeOnDelete();
            $table->string('scope_type')->default('global');
            $table->string('scope_key')->nullable();
            $table->string('actor_role')->default('user');
            $table->string('plan_code')->nullable();
            $table->string('feature_code')->nullable();
            $table->string('storage_mode')->nullable();
            $table->string('domain_mode')->nullable();
            $table->string('source_system')->default('aifut-core');
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->nullable();
            $table->json('conditions')->nullable();
            $table->timestamps();

            $table->index(['scope_type', 'scope_key', 'actor_role']);
            $table->index(['plan_code', 'feature_code']);
            $table->index(['storage_mode', 'domain_mode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aifut_menu_rules');
    }
};
