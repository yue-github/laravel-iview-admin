<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateLabels extends Migration {
    public function up() {
        if (Schema::hasTable('labels')){
            Schema::table('labels', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('labels');
                $newColumn = [
                    'id',
                    'name',
                    'sort',
                    'parent_id',
                    'onsale',
                    'shop_id',
                    'id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('labels', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: labels, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('labels', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: labels, column: sort, type: integer');
                    }
                }
                                if (!Schema::hasColumn('labels', 'parent_id')){
                    $table->bigInteger('parent_id')->default('0');
                } else {
                    try {
                        // $table->bigInteger('parent_id')->default('0')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: labels, column: parent_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('labels', 'onsale')){
                    $table->integer('onsale')->nullable();
                } else {
                    try {
                        // $table->integer('onsale')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: labels, column: onsale, type: integer');
                    }
                }
                                if (!Schema::hasColumn('labels', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: labels, column: shop_id, type: bigInteger');
                    }
                }
                                                });
            } else {
                Schema::create('labels', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->integer('sort')->nullable();
                    $table->bigInteger('parent_id')->default('0');
                    $table->integer('onsale')->nullable();
                    $table->bigInteger('shop_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('labels');
    }
}