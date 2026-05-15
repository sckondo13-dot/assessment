<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            // 現場
            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnDelete();
            // 評価する人
            $table->foreignId('evaluator_user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // 評価される人
            $table->foreignId('target_user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // 送信日時
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // 同じ現場で同じ組み合わせは1回だけ
            $table->unique([
                'site_id',
                'evaluator_user_id',
                'target_user_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
