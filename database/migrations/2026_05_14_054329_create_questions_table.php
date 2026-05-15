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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // 現場
            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnDelete();
            // 評価される側の役職
            $table->foreignId('target_role_id')
                ->constrained('roles')
                ->cascadeOnDelete();
            // 質問
            $table->text('question_text');

            /**
             * yes_no
             * comment
             * score
             */
            $table->string('type')->default('yes_no');
            // 必須か
            $table->boolean('is_required')->default(true);
            // 表示順
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
