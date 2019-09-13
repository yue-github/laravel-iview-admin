<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductHeader extends Migration {
    public function up() {
        if (Schema::hasTable('product_header')){
            Schema::table('product_header', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_header');
                $newColumn = [
                    'id',
                    'img_file_name',
                    'url',
                    'content',
                    'package_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('product_header', 'img_file_name')){
                    $table->string('img_file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('img_file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_header, column: img_file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_header', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_header, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_header', 'content')){
                    $table->string('content', 100)->nullable();
                } else {
                    try {
                        // $table->string('content', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_header, column: content, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_header', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_header, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('product_header', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('product_header', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('img_file_name', 100)->nullable();
                    $table->text('url')->nullable();
                    $table->string('content', 100)->nullable();
                    $table->bigInteger('package_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('product_header');
    }
}