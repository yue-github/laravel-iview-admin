<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductNotices extends Migration {
    public function up() {
        if (Schema::hasTable('product_notices')){
            Schema::table('product_notices', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_notices');
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
                if (!Schema::hasColumn('product_notices', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_notices, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_notices', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_notices, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_notices', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_notices, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_notices', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_notices, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('product_notices', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('product_notices', function (Blueprint $table) {
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
        Schema::dropIfExists('product_notices');
    }
}