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
        Schema::create('site_members', function (Blueprint $table) {
            $table->id();
            // 現場
            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnDelete();
            // 従業員
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            // 役職
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();
            // 並び順
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            // 同一現場で同じ人を重複登録させない
            $table->unique(['site_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_members');
    }
};
