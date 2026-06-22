<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('sku');
            $table->string('featured_image')->nullable()->after('stock');
        });

        DB::table('products')
            ->whereNull('featured_image')
            ->update([
                'featured_image' => DB::raw('image_url'),
            ]);

        DB::table('products')
            ->whereNull('brand')
            ->update([
                'brand' => 'GearTech',
            ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'featured_image']);
        });
    }
};
