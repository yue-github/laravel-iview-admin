<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSearchHistory extends Migration {
    public function up() {
        if (Schema::hasTable('search_history')){
            Schema::table('search_history', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('search_history');
                $newColumn = [
                    'id',
                    'user_id',
                    'search_content',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('search_history', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: search_history, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('search_history', 'search_content')){
                    $table->string('search_content', 100)->nullable();
                } else {
                    try {
                        // $table->string('search_content', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: search_history, column: search_content, type: string');
                    }
                }
                                if (!Schema::hasColumn('search_history', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('search_history', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('user_id')->nullable();
                    $table->string('search_content', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('search_history');
    }
}