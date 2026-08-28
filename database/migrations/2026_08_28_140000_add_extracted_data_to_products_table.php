<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('color_palette')->nullable()->after('weight');
            $table->string('color_primary')->nullable()->after('color_palette');
            $table->string('color_secondary')->nullable()->after('color_primary');
            $table->string('condition')->nullable()->after('color_secondary');
            $table->string('age_estimate')->nullable()->after('condition');
            $table->text('style_notes')->nullable()->after('age_estimate');
            $table->integer('height_cm')->nullable()->after('style_notes');
            $table->integer('width_cm')->nullable()->after('height_cm');
            $table->integer('depth_cm')->nullable()->after('width_cm');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'color_palette', 'color_primary', 'color_secondary',
                'condition', 'age_estimate', 'style_notes',
                'height_cm', 'width_cm', 'depth_cm',
            ]);
        });
    }
};
