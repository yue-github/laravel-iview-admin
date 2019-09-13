<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateShoppingCart extends Migration {
    public function up() {
        if (Schema::hasTable('shopping_cart')){
            Schema::table('shopping_cart', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('shopping_cart');
                $newColumn = [
                    'id',
                    'product_id',
                    'shop_id',
                    'selected',
                    'user_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('shopping_cart', 'product_id')){
                    $table->bigInteger('product_id');
                } else {
                    try {
                        // $table->bigInteger('product_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shopping_cart, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('shopping_cart', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shopping_cart, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('shopping_cart', 'selected')){
                    $table->boolean('selected')->default('0')->nullable();
                } else {
                    try {
                        // $table->boolean('selected')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shopping_cart, column: selected, type: boolean');
                    }
                }
                                if (!Schema::hasColumn('shopping_cart', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shopping_cart, column: user_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('shopping_cart', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('product_id');
                    $table->bigInteger('shop_id')->nullable();
                    $table->boolean('selected')->default('0')->nullable();
                    $table->bigInteger('user_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('shopping_cart');
    }
}