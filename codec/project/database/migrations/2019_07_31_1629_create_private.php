<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePrivate extends Migration {
    public function up() {
        if (Schema::hasTable('private')){
            Schema::table('private', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('private');
                $newColumn = [
                    'id',
                    'content',
                    'purchased_id',
                    'resource_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('private', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: private, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('private', 'purchased_id')){
                    $table->bigInteger('purchased_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('purchased_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: private, column: purchased_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('private', 'resource_id')){
                    $table->bigInteger('resource_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('resource_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: private, column: resource_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('private', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('private', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->text('content')->nullable();
                    $table->bigInteger('purchased_id')->nullable();
                    $table->bigInteger('resource_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('private');
    }
}