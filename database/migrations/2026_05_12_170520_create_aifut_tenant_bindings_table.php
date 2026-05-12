<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aifut_tenant_bindings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tenant_code')->index();
            $table->string('workspace_code')->nullable();
            $table->string('source_system')->default('aifut-core');
            $table->string('plan_code')->nullable();
            $table->string('storage_mode')->nullable();
            $table->string('domain_mode')->nullable();
            $table->json('capabilities')->nullable();
            $table->json('sync_meta')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tenant_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aifut_tenant_bindings');
    }
};
