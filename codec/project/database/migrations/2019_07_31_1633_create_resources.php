<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateResources extends Migration {
    public function up() {
        if (Schema::hasTable('resources')){
            Schema::table('resources', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('resources');
                $newColumn = [
                    'id',
                    'shop_id',
                    'name',
                    'data',
                    'type',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('resources', 'shop_id')){
                    $table->bigInteger('shop_id');
                } else {
                    try {
                        // $table->bigInteger('shop_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: resources, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('resources', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: resources, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('resources', 'data')){
                    $table->text('data');
                } else {
                    try {
                        // $table->text('data')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: resources, column: data, type: text');
                    }
                }
                                if (!Schema::hasColumn('resources', 'type')){
                    $table->integer('type');
                } else {
                    try {
                        // $table->integer('type')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: resources, column: type, type: integer');
                    }
                }
                                if (!Schema::hasColumn('resources', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('resources', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('shop_id');
                    $table->string('name', 100);
                    $table->text('data');
                    $table->integer('type');
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('resources');
    }
}