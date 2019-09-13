<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePolyv extends Migration {
    public function up() {
        if (Schema::hasTable('polyv')){
            Schema::table('polyv', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('polyv');
                $newColumn = [
                    'id',
                    'id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                                });
            } else {
                Schema::create('polyv', function (Blueprint $table) {
                    $table->bigIncrements('id');
                });
            }
        }
    public function down() {
        Schema::dropIfExists('polyv');
    }
}