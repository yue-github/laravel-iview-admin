<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateCers extends Migration {
    public function up() {
        if (Schema::hasTable('cers')){
            Schema::table('cers', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('cers');
                $newColumn = [
                    'id',
                    'purchased_id',
                    'cer_year',
                    'pay_date',
                    'end_date',
                    'owner_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('cers', 'purchased_id')){
                    $table->text('purchased_id')->nullable();
                } else {
                    try {
                        // $table->text('purchased_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: cers, column: purchased_id, type: text');
                    }
                }
                                if (!Schema::hasColumn('cers', 'cer_year')){
                    $table->integer('cer_year')->nullable();
                } else {
                    try {
                        // $table->integer('cer_year')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: cers, column: cer_year, type: integer');
                    }
                }
                                if (!Schema::hasColumn('cers', 'pay_date')){
                    $table->dateTime('pay_date')->nullable();
                } else {
                    try {
                        // $table->dateTime('pay_date')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: cers, column: pay_date, type: dateTime');
                    }
                }
                                if (!Schema::hasColumn('cers', 'end_date')){
                    $table->dateTime('end_date')->nullable();
                } else {
                    try {
                        // $table->dateTime('end_date')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: cers, column: end_date, type: dateTime');
                    }
                }
                                if (!Schema::hasColumn('cers', 'owner_id')){
                    $table->bigInteger('owner_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('owner_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: cers, column: owner_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('cers', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('cers', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->text('purchased_id')->nullable();
                    $table->integer('cer_year')->nullable();
                    $table->dateTime('pay_date')->nullable();
                    $table->dateTime('end_date')->nullable();
                    $table->bigInteger('owner_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('cers');
    }
}