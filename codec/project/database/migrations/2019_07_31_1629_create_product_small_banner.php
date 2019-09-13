<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductSmallBanner extends Migration {
    public function up() {
        if (Schema::hasTable('product_small_banner')){
            Schema::table('product_small_banner', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_small_banner');
                $newColumn = [
                    'id',
                    'img_file_name',
                    'url',
                    'package_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('product_small_banner', 'img_file_name')){
                    $table->string('img_file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('img_file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_small_banner, column: img_file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_small_banner', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_small_banner, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_small_banner', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_small_banner, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('product_small_banner', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('product_small_banner', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('img_file_name', 100)->nullable();
                    $table->text('url')->nullable();
                    $table->bigInteger('package_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('product_small_banner');
    }
}