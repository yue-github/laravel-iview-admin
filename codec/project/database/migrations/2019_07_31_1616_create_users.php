<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateUsers extends Migration {
    public function up() {
        if (Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('users');
                $newColumn = [
                    'id',
                    'open_id',
                    'shop_id',
                    'name',
                    'idcard',
                    'company',
                    'phone',
                    'email',
                    'enabled',
                    'isLookVideo',
                    'password',
                    'balance',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('users', 'open_id')){
                    $table->string('open_id', 100)->nullable();
                } else {
                    try {
                        // $table->string('open_id', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: open_id, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('users', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'idcard')){
                    $table->string('idcard', 18)->nullable();
                } else {
                    try {
                        // $table->string('idcard', 18)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: idcard, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'company')){
                    $table->string('company', 500)->nullable();
                } else {
                    try {
                        // $table->string('company', 500)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: company, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'phone')){
                    $table->string('phone', 11);
                } else {
                    try {
                        // $table->string('phone', 11)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: phone, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'email')){
                    $table->string('email', 200)->nullable();
                } else {
                    try {
                        // $table->string('email', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: email, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'enabled')){
                    $table->boolean('enabled')->default('1')->nullable();
                } else {
                    try {
                        // $table->boolean('enabled')->default('1')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: enabled, type: boolean');
                    }
                }
                                if (!Schema::hasColumn('users', 'isLookVideo')){
                    $table->integer('isLookVideo')->default('0')->nullable();
                } else {
                    try {
                        // $table->integer('isLookVideo')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: isLookVideo, type: integer');
                    }
                }
                                if (!Schema::hasColumn('users', 'password')){
                    $table->string('password', 64);
                } else {
                    try {
                        // $table->string('password', 64)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: password, type: string');
                    }
                }
                                if (!Schema::hasColumn('users', 'balance')){
                    $table->decimal('balance', 13, 2)->default('0')->nullable();
                } else {
                    try {
                        // $table->decimal('balance', 13, 2)->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: users, column: balance, type: decimal');
                    }
                }
                                if (!Schema::hasColumn('users', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('users', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('open_id', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->string('name', 100)->nullable();
                    $table->string('idcard', 18)->nullable();
                    $table->string('company', 500)->nullable();
                    $table->string('phone', 11);
                    $table->string('email', 200)->nullable();
                    $table->boolean('enabled')->default('1')->nullable();
                    $table->integer('isLookVideo')->default('0')->nullable();
                    $table->string('password', 64);
                    $table->decimal('balance', 13, 2)->default('0')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('users');
    }
}