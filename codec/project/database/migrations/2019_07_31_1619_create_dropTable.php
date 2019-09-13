<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateDropTable extends Migration {
    public function up() {
        $oldTable = array();
        $tables = DB::select('show tables');
        foreach ($tables as $table) {
            foreach ($table as $key => $value)
                array_push($oldTable, $value);
        }
          $newTable = [ 
              'users', 
              'labels', 
              'shop_labels', 
              'resources', 
              'activities', 
              'task_activities', 
              'tasks', 
              'project_tasks', 
              'projects', 
              'shopping_cart', 
              'product_labels', 
              'products', 
              'shops', 
              'orders', 
              'purchased', 
              'cers', 
              'sections', 
              'section_products', 
              'instructor', 
              'class', 
              'evaluates', 
              'class_users', 
              'class_bulletin', 
              'class_file', 
              'class_bulletin_class_file', 
              'file', 
              'md5', 
              'polyv', 
              'selected', 
              'search_history', 
              'selected_batch', 
              'discuss_theme', 
              'comment', 
              'banner', 
              'product_news', 
              'product_notices', 
              'product_header', 
              'product_small_banner', 
              'product_start_notice', 
              'product_operation_guide', 
              'icon', 
              'icon_type', 
              'private', 
              'answ_question', 
              'answ_question_comment', 
              'package', 
              'package_product', 
              'proportion', 
              'package_section', 
              'sms', 
              'attribute_value', 
              'attribute', 
              'package_attribute_value', 
              'attribute_value_type', 
              'navigation_bar', 
          ]; 
        $dropTable = array_diff($oldTable, $newTable);
        foreach ($dropTable as $item) {
            if ($item != 'migrations')
            Schema::dropIfExists($item);
        }
    }
    public function down() {

    }
}