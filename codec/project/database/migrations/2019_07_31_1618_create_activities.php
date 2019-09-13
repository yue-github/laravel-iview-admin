<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateActivities extends Migration {
    public function up() {
        if (Schema::hasTable('activities')){
            Schema::table('activities', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('activities');
                $newColumn = [
                    'id',
                    'shop_id',
                    'name',
                    'desc',
                    'resources',
                    'sort',
                    'icon_type_id',
                    'id_delete',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('activities', 'shop_id')){
                    $table->bigInteger('shop_id');
                } else {
                    try {
                        // $table->bigInteger('shop_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('activities', 'name')){
                    $table->text('name');
                } else {
                    try {
                        // $table->text('name')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: name, type: text');
                    }
                }
                                if (!Schema::hasColumn('activities', 'desc')){
                    $table->text('desc')->nullable();
                } else {
                    try {
                        // $table->text('desc')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: desc, type: text');
                    }
                }
                                if (!Schema::hasColumn('activities', 'resources')){
                    $table->text('resources')->nullable();
                } else {
                    try {
                        // $table->text('resources')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: resources, type: text');
                    }
                }
                                if (!Schema::hasColumn('activities', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: sort, type: integer');
                    }
                }
                                if (!Schema::hasColumn('activities', 'icon_type_id')){
                    $table->bigInteger('icon_type_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('icon_type_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: icon_type_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('activities', 'id_delete')){
                    $table->integer('id_delete')->nullable();
                } else {
                    try {
                        // $table->integer('id_delete')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: activities, column: id_delete, type: integer');
                    }
                }
                                if (!Schema::hasColumn('activities', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('activities', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('shop_id');
                    $table->text('name');
                    $table->text('desc')->nullable();
                    $table->text('resources')->nullable();
                    $table->integer('sort')->nullable();
                    $table->bigInteger('icon_type_id')->nullable();
                    $table->integer('id_delete')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('activities');
    }
}