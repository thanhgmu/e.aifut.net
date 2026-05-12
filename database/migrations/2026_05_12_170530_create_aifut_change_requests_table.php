<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aifut_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('request_type');
            $table->string('status')->default('pending');
            $table->string('tenant_code')->nullable()->index();
            $table->string('target_system')->default('aifut-core');
            $table->json('payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['request_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aifut_change_requests');
    }
};
