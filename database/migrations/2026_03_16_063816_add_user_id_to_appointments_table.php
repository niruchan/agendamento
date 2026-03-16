<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// クラス名はファイル名と一致している必要があります
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // もし既にテーブルにデータがある場合を考慮して、
            // user_id を追加。既存データは ID 1 のユーザーに紐付けるなどの処理が必要ですが、
            // まずはカラムを追加します。
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};