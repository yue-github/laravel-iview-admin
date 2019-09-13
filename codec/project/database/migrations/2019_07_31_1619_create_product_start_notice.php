<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductStartNotice extends Migration {
    public function up() {
        if (Schema::hasTable('product_start_notice')){
            Schema::table('product_start_notice', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_start_notice');
                $newColumn = [
                    'id',
                    'title',
                    'url',
                    'content',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('product_start_notice', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_start_notice, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_start_notice', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_start_notice, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('product_start_notice', 'content')){
                    $table->string('content', 100)->nullable();
                } else {
                    try {
                        // $table->string('content', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_start_notice, column: content, type: string');
                    }
                }
                                if (!Schema::hasColumn('product_start_notice', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('product_start_notice', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('title', 100)->nullable();
                    $table->text('url')->nullable();
                    $table->string('content', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('product_start_notice');
    }
}