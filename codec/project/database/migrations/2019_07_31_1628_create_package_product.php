<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePackageProduct extends Migration {
    public function up() {
        if (Schema::hasTable('package_product')){
            Schema::table('package_product', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('package_product');
                $newColumn = [
                    'id',
                    'package_id',
                    'product_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('package_product', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_product, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('package_product', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_product, column: product_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('package_product', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('package_id')->nullable();
                    $table->bigInteger('product_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('package_product');
    }
}