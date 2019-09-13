<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProportion extends Migration {
    public function up() {
        if (Schema::hasTable('proportion')){
            Schema::table('proportion', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('proportion');
                $newColumn = [
                    'id',
                    'resources',
                    'product_id',
                    'shop_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('proportion', 'resources')){
                    $table->text('resources')->nullable();
                } else {
                    try {
                        // $table->text('resources')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: proportion, column: resources, type: text');
                    }
                }
                                if (!Schema::hasColumn('proportion', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: proportion, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('proportion', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: proportion, column: shop_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('proportion', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->text('resources')->nullable();
                    $table->bigInteger('product_id')->nullable();
                    $table->bigInteger('shop_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('proportion');
    }
}