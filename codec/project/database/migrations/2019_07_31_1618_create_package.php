<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePackage extends Migration {
    public function up() {
        if (Schema::hasTable('package')){
            Schema::table('package', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('package');
                $newColumn = [
                    'id',
                    'name',
                    'shop_id',
                    'title2',
                    'img',
                    'background',
                    'title1',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('package', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('package', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('package', 'title2')){
                    $table->string('title2', 100)->nullable();
                } else {
                    try {
                        // $table->string('title2', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: title2, type: string');
                    }
                }
                                if (!Schema::hasColumn('package', 'img')){
                    $table->string('img', 100)->nullable();
                } else {
                    try {
                        // $table->string('img', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: img, type: string');
                    }
                }
                                if (!Schema::hasColumn('package', 'background')){
                    $table->string('background', 100)->nullable();
                } else {
                    try {
                        // $table->string('background', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: background, type: string');
                    }
                }
                                if (!Schema::hasColumn('package', 'title1')){
                    $table->string('title1', 100)->nullable();
                } else {
                    try {
                        // $table->string('title1', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package, column: title1, type: string');
                    }
                }
                                if (!Schema::hasColumn('package', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('package', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->string('title2', 100)->nullable();
                    $table->string('img', 100)->nullable();
                    $table->string('background', 100)->nullable();
                    $table->string('title1', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('package');
    }
}