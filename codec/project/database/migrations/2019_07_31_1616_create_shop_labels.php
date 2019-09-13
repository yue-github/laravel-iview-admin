<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateShopLabels extends Migration {
    public function up() {
        if (Schema::hasTable('shop_labels')){
            Schema::table('shop_labels', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('shop_labels');
                $newColumn = [
                    'id',
                    'name',
                    'sort',
                    'parent_id',
                    'shop_id',
                    'onsale',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('shop_labels', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shop_labels, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shop_labels', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shop_labels, column: sort, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shop_labels', 'parent_id')){
                    $table->bigInteger('parent_id')->default('0');
                } else {
                    try {
                        // $table->bigInteger('parent_id')->default('0')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shop_labels, column: parent_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('shop_labels', 'shop_id')){
                    $table->bigInteger('shop_id')->default('0');
                } else {
                    try {
                        // $table->bigInteger('shop_id')->default('0')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shop_labels, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('shop_labels', 'onsale')){
                    $table->integer('onsale')->nullable();
                } else {
                    try {
                        // $table->integer('onsale')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shop_labels, column: onsale, type: integer');
                    }
                }
                                });
            } else {
                Schema::create('shop_labels', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->integer('sort')->nullable();
                    $table->bigInteger('parent_id')->default('0');
                    $table->bigInteger('shop_id')->default('0');
                    $table->integer('onsale')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('shop_labels');
    }
}