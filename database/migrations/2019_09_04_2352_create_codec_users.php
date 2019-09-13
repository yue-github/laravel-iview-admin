<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateCodecUsers extends Migration {
    public function up() {
        if (Schema::hasTable('codec_users')){
            Schema::table('codec_users', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('codec_users');
                $newColumn = [
                    'id',
                    'name',
                    'shop_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('codec_users', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: codec_users, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('codec_users', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: codec_users, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('codec_users', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('codec_users', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('codec_users');
    }
}