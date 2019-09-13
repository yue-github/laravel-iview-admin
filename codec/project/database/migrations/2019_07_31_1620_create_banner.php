<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateBanner extends Migration {
    public function up() {
        if (Schema::hasTable('banner')){
            Schema::table('banner', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('banner');
                $newColumn = [
                    'id',
                    'name',
                    'url',
                    'file_name',
                    'shop_id',
                    'sort',
                    'color',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('banner', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('banner', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('banner', 'file_name')){
                    $table->string('file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('banner', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('banner', 'sort')){
                    $table->bigInteger('sort')->nullable();
                } else {
                    try {
                        // $table->bigInteger('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: sort, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('banner', 'color')){
                    $table->string('color', 100)->nullable();
                } else {
                    try {
                        // $table->string('color', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: banner, column: color, type: string');
                    }
                }
                                if (!Schema::hasColumn('banner', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('banner', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->text('url')->nullable();
                    $table->string('file_name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->bigInteger('sort')->nullable();
                    $table->string('color', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('banner');
    }
}