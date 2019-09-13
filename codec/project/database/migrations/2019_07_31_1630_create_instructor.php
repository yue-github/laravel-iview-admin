<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateInstructor extends Migration {
    public function up() {
        if (Schema::hasTable('instructor')){
            Schema::table('instructor', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('instructor');
                $newColumn = [
                    'id',
                    'name',
                    'idcard',
                    'company',
                    'phone',
                    'email',
                    'desc',
                    'open_id',
                    'img',
                    'shop_id',
                    'enabled',
                    'password',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('instructor', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'idcard')){
                    $table->string('idcard', 18)->nullable();
                } else {
                    try {
                        // $table->string('idcard', 18)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: idcard, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'company')){
                    $table->string('company', 100)->nullable();
                } else {
                    try {
                        // $table->string('company', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: company, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'phone')){
                    $table->string('phone', 11);
                } else {
                    try {
                        // $table->string('phone', 11)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: phone, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'email')){
                    $table->string('email', 200)->nullable();
                } else {
                    try {
                        // $table->string('email', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: email, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'desc')){
                    $table->string('desc', 100)->nullable();
                } else {
                    try {
                        // $table->string('desc', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: desc, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'open_id')){
                    $table->string('open_id', 100)->nullable();
                } else {
                    try {
                        // $table->string('open_id', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: open_id, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'img')){
                    $table->string('img', 100)->nullable();
                } else {
                    try {
                        // $table->string('img', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: img, type: string');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'enabled')){
                    $table->boolean('enabled')->default('1')->nullable();
                } else {
                    try {
                        // $table->boolean('enabled')->default('1')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: enabled, type: boolean');
                    }
                }
                                if (!Schema::hasColumn('instructor', 'password')){
                    $table->string('password', 64);
                } else {
                    try {
                        // $table->string('password', 64)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: instructor, column: password, type: string');
                    }
                }
                                });
            } else {
                Schema::create('instructor', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100);
                    $table->string('idcard', 18)->nullable();
                    $table->string('company', 100)->nullable();
                    $table->string('phone', 11);
                    $table->string('email', 200)->nullable();
                    $table->string('desc', 100)->nullable();
                    $table->string('open_id', 100)->nullable();
                    $table->string('img', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->boolean('enabled')->default('1')->nullable();
                    $table->string('password', 64);
                });
            }
        }
    public function down() {
        Schema::dropIfExists('instructor');
    }
}