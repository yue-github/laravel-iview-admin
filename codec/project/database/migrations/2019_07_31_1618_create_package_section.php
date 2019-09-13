<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePackageSection extends Migration {
    public function up() {
        if (Schema::hasTable('package_section')){
            Schema::table('package_section', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('package_section');
                $newColumn = [
                    'id',
                    'package_id',
                    'section_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('package_section', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_section, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('package_section', 'section_id')){
                    $table->bigInteger('section_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('section_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_section, column: section_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('package_section', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('package_id')->nullable();
                    $table->bigInteger('section_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('package_section');
    }
}