<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSelectedBatch extends Migration {
    public function up() {
        if (Schema::hasTable('selected_batch')){
            Schema::table('selected_batch', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('selected_batch');
                $newColumn = [
                    'id',
                    'batch_num',
                    'remarks',
                    'state',
                    'created_at',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('selected_batch', 'batch_num')){
                    $table->string('batch_num', 100)->nullable();
                } else {
                    try {
                        // $table->string('batch_num', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected_batch, column: batch_num, type: string');
                    }
                }
                                if (!Schema::hasColumn('selected_batch', 'remarks')){
                    $table->string('remarks', 100)->nullable();
                } else {
                    try {
                        // $table->string('remarks', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected_batch, column: remarks, type: string');
                    }
                }
                                if (!Schema::hasColumn('selected_batch', 'state')){
                    $table->integer('state')->default('1')->nullable();
                } else {
                    try {
                        // $table->integer('state')->default('1')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: selected_batch, column: state, type: integer');
                    }
                }
                                                if (!Schema::hasColumn('selected_batch', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('selected_batch', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('batch_num', 100)->nullable();
                    $table->string('remarks', 100)->nullable();
                    $table->integer('state')->default('1')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('selected_batch');
    }
}