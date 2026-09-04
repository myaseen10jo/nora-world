<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('return_policy')->nullable()->after('gift_wrapping_available');
            $table->string('clothing_size')->nullable()->after('return_policy');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['return_policy', 'clothing_size']);
        });
    }
};
