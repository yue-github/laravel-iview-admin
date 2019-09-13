<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSections extends Migration {
    public function up() {
        if (Schema::hasTable('sections')){
            Schema::table('sections', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('sections');
                $newColumn = [
                    'id',
                    'shop_id',
                    'page',
                    'desc',
                    'template',
                    'name',
                    'sort',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('sections', 'shop_id')){
                    $table->bigInteger('shop_id')->default('0')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('sections', 'page')){
                    $table->integer('page')->nullable();
                } else {
                    try {
                        // $table->integer('page')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: page, type: integer');
                    }
                }
                                if (!Schema::hasColumn('sections', 'desc')){
                    $table->string('desc', 100)->nullable();
                } else {
                    try {
                        // $table->string('desc', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: desc, type: string');
                    }
                }
                                if (!Schema::hasColumn('sections', 'template')){
                    $table->integer('template')->nullable();
                } else {
                    try {
                        // $table->integer('template')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: template, type: integer');
                    }
                }
                                if (!Schema::hasColumn('sections', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('sections', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sections, column: sort, type: integer');
                    }
                }
                                });
            } else {
                Schema::create('sections', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('shop_id')->default('0')->nullable();
                    $table->integer('page')->nullable();
                    $table->string('desc', 100)->nullable();
                    $table->integer('template')->nullable();
                    $table->string('name', 100)->nullable();
                    $table->integer('sort')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('sections');
    }
}