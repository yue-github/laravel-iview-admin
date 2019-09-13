<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSelected extends Migration {
    public function up() {
        if (Schema::hasTable('selected')){
            Schema::table('selected', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('selected');
                $newColumn = [
                    'id',
                    'selected',
                    'user_id',
                    'product_id',
                    'selected_batch_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('selected', 'selected')){
                    $table->integer('selected')->nullable();
                } else {
                    try {
                        // $table->integer('selected')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected, column: selected, type: integer');
                    }
                }
                                if (!Schema::hasColumn('selected', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('selected', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('selected', 'selected_batch_id')){
                    $table->bigInteger('selected_batch_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('selected_batch_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected, column: selected_batch_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('selected', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('selected', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('selected')->nullable();
                    $table->bigInteger('user_id')->nullable();
                    $table->bigInteger('product_id')->nullable();
                    $table->bigInteger('selected_batch_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('selected');
    }
}