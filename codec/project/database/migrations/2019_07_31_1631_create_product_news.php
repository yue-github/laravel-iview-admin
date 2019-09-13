<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductNews extends Migration {
    public function up() {
        if (Schema::hasTable('product_news')){
            Schema::table('product_news', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_news');
                $newColumn = [
                    'id',
                    'title',
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
                if (!Schema::hasColumn('product_news', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_news, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_news', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_news, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_news', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_news, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_news', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_news, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('product_news', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('product_news', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('title', 100)->nullable();
                    $table->text('url')->nullable();
                    $table->text('content')->nullable();
                    $table->bigInteger('package_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('product_news');
    }
}