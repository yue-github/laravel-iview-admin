<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSectionProducts extends Migration {
    public function up() {
        if (Schema::hasTable('section_products')){
            Schema::table('section_products', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('section_products');
                $newColumn = [
                    'id',
                    'section_id',
                    'product_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('section_products', 'section_id')){
                    $table->bigInteger('section_id');
                } else {
                    try {
                        // $table->bigInteger('section_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: section_products, column: section_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('section_products', 'product_id')){
                    $table->bigInteger('product_id');
                } else {
                    try {
                        // $table->bigInteger('product_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: section_products, column: product_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('section_products', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('section_id');
                    $table->bigInteger('product_id');
                });
            }
        }
    public function down() {
        Schema::dropIfExists('section_products');
    }
}