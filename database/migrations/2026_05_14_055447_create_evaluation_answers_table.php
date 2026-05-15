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
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            // 評価ヘッダ
            $table->foreignId('evaluation_id')
                ->constrained()
                ->cascadeOnDelete();
            // 質問
            $table->foreignId('question_id')
                ->constrained()
                ->cascadeOnDelete();
            // YES / NO
            $table->boolean('answer_bool')->nullable();
            // コメント
            $table->text('answer_text')->nullable();
            // 点数
            $table->integer('answer_score')->nullable();
            // 並び順
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
        Schema::dropIfExists('evaluation_answers');
    }
};
