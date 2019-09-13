<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateAttributeValue extends Migration {
    public function up() {
        if (Schema::hasTable('attribute_value')){
            Schema::table('attribute_value', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('attribute_value');
                $newColumn = [
                    'id',
                    'name',
                    'shop_id',
                    'sort',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('attribute_value', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute_value, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('attribute_value', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute_value, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('attribute_value', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute_value, column: sort, type: integer');
                    }
                }
                                });
            } else {
                Schema::create('attribute_value', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->integer('sort')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('attribute_value');
    }
}