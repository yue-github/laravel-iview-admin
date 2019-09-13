<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateNavigationBar extends Migration {
    public function up() {
        if (Schema::hasTable('navigation_bar')){
            Schema::table('navigation_bar', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('navigation_bar');
                $newColumn = [
                    'id',
                    'name',
                    'url',
                    'shop_id',
                    'sort',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('navigation_bar', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: navigation_bar, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('navigation_bar', 'url')){
                    $table->string('url', 200)->nullable();
                } else {
                    try {
                        // $table->string('url', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: navigation_bar, column: url, type: string');
                    }
                }
                                if (!Schema::hasColumn('navigation_bar', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: navigation_bar, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('navigation_bar', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: navigation_bar, column: sort, type: integer');
                    }
                }
                                });
            } else {
                Schema::create('navigation_bar', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->string('url', 200)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->integer('sort')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('navigation_bar');
    }
}