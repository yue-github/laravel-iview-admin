<?php
Route::post('/users/wx_login', 'UsersController@wx_login');
Route::post('/users/bind_user', 'UsersController@bind_user');
Route::post('/users/bind_instructor', 'UsersController@bind_instructor');
Route::post('/users/login', 'UsersController@login');
Route::post('/users/login_by_idcard', 'UsersController@login_by_idcard');
Route::post('/users/sms_send', 'UsersController@sms_send');
Route::post('/users/set_password', 'UsersController@set_password');
Route::post('/labels/search', 'LabelsController@search');
Route::post('/labels/all', 'LabelsController@all');
Route::post('/resources/batch_insert', 'ResourcesController@batch_insert');
Route::post('/projects/search', 'ProjectsController@search');
Route::post('/product_labels/listLabels', 'ProductLabelsController@listLabels');
Route::post('/product_labels/listProducts', 'ProductLabelsController@listProducts');
Route::post('/products/update_activity', 'ProductsController@update_activity');
Route::post('/products/get_activities_by_product_id', 'ProductsController@get_activities_by_product_id');
Route::post('/products/get_banner_img', 'ProductsController@get_banner_img');
Route::post('/products/get_task_by_product_id', 'ProductsController@get_task_by_product_id');
Route::post('/products/get', 'ProductsController@get');
Route::post('/products/get_pay', 'ProductsController@get_pay');
Route::post('/products/search', 'ProductsController@search');
Route::post('/shops/get', 'ShopsController@get');
Route::post('/shops/shop_create', 'ShopsController@shop_create');
Route::post('/shops/shop_search', 'ShopsController@shop_search');
Route::post('/shops/shop_edit', 'ShopsController@shop_edit');
Route::post('/shops/shop_delete', 'ShopsController@shop_delete');
Route::post('/shops/get_url', 'ShopsController@get_url');
Route::post('/shops/get_shopid', 'ShopsController@get_shopid');
Route::post('/orders/alipay_callback', 'OrdersController@alipay_callback');
Route::post('/orders/wxpay_callback', 'OrdersController@wxpay_callback');
Route::post('/orders/wxpay_js_callback', 'OrdersController@wxpay_js_callback');
Route::post('/orders/get_money', 'OrdersController@get_money');
Route::post('/orders/get_money_token', 'OrdersController@get_money_token');
Route::post('/sections/search', 'SectionsController@search');
Route::post('/sections/search_package', 'SectionsController@search_package');
Route::post('/section_products/search', 'SectionProductsController@search');
Route::post('/instructor/login', 'InstructorController@login');
Route::post('/class/search_count', 'ClassController@search_count');
Route::post('/evaluates/search', 'EvaluatesController@search');
Route::post('/class_bulletin/search', 'ClassBulletinController@search');
Route::post('/class_file/search', 'ClassFileController@search');
Route::post('/md5/get', 'Md5Controller@get');
Route::post('/polyv/upload_call_back', 'PolyvController@upload_call_back');
Route::post('/banner/search', 'BannerController@search');
Route::post('/banner/shop_search', 'BannerController@shop_search');
Route::post('/product_small_banner/search', 'ProductSmallBannerController@search');
Route::post('/product_small_banner/insert', 'ProductSmallBannerController@insert');
Route::post('/product_small_banner/delete', 'ProductSmallBannerController@delete');
Route::post('/product_small_banner/banner', 'ProductSmallBannerController@banner');
Route::post('/product_small_banner/update', 'ProductSmallBannerController@update');
Route::post('/answ_question/search_by_instructor', 'AnswQuestionController@search_by_instructor');
Route::post('/package/search', 'PackageController@search');
Route::post('/package/search_package', 'PackageController@search_package');
Route::post('/package_product/search', 'PackageProductController@search');
Route::post('/proportion/create', 'ProportionController@create');
Route::post('/proportion/get', 'ProportionController@get');
Route::post('/proportion/edit', 'ProportionController@edit');
Route::post('/attribute/search', 'AttributeController@search');
Route::post('/navigation_bar/search', 'NavigationBarController@search');
Route::group(["middleware" => "shop"], function(){
Route::post('/users/batchRechargeByUserId', 'UsersController@batchRechargeByUserId');
Route::post('/users/batchRechargeByIdcard', 'UsersController@batchRechargeByIdcard');
Route::post('/users/shop_edit', 'UsersController@shop_edit');
Route::post('/users/search_pay_project', 'UsersController@search_pay_project');
Route::post('/users/search', 'UsersController@search');
Route::post('/users/getTokenByUserId', 'UsersController@getTokenByUserId');
Route::post('/users/register', 'UsersController@register');
Route::post('/labels/edit', 'LabelsController@edit');
Route::post('/labels/create', 'LabelsController@create');
Route::post('/labels/delete', 'LabelsController@delete');
Route::post('/shop_labels/search', 'ShopLabelsController@search');
Route::post('/shop_labels/all', 'ShopLabelsController@all');
Route::post('/shop_labels/edit', 'ShopLabelsController@edit');
Route::post('/shop_labels/create', 'ShopLabelsController@create');
Route::post('/shop_labels/delete', 'ShopLabelsController@delete');
Route::post('/resources/create_discuss', 'ResourcesController@create_discuss');
Route::post('/resources/excel_topic', 'ResourcesController@excel_topic');
Route::post('/resources/shop_search', 'ResourcesController@shop_search');
Route::post('/resources/shop_search_not_data', 'ResourcesController@shop_search_not_data');
Route::post('/resources/shop_create', 'ResourcesController@shop_create');
Route::post('/resources/shop_edit', 'ResourcesController@shop_edit');
Route::post('/resources/shop_get', 'ResourcesController@shop_get');
Route::post('/resources/delete', 'ResourcesController@delete');
Route::post('/activities/search', 'ActivitiesController@search');
Route::post('/activities/create', 'ActivitiesController@create');
Route::post('/activities/edit', 'ActivitiesController@edit');
Route::post('/activities/delete', 'ActivitiesController@delete');
Route::post('/activities/shop_edit', 'ActivitiesController@shop_edit');
Route::post('/task_activities/delete', 'TaskActivitiesController@delete');
Route::post('/task_activities/update', 'TaskActivitiesController@update');
Route::post('/task_activities/create', 'TaskActivitiesController@create');
Route::post('/task_activities/create_one', 'TaskActivitiesController@create_one');
Route::post('/task_activities/search', 'TaskActivitiesController@search');
Route::post('/tasks/create', 'TasksController@create');
Route::post('/tasks/edit', 'TasksController@edit');
Route::post('/tasks/get', 'TasksController@get');
Route::post('/tasks/delete', 'TasksController@delete');
Route::post('/tasks/shop_search', 'TasksController@shop_search');
Route::post('/project_tasks/create_product_task', 'ProjectTasksController@create_product_task');
Route::post('/project_tasks/create', 'ProjectTasksController@create');
Route::post('/project_tasks/delete', 'ProjectTasksController@delete');
Route::post('/project_tasks/edit', 'ProjectTasksController@edit');
Route::post('/projects/create', 'ProjectsController@create');
Route::post('/projects/edit', 'ProjectsController@edit');
Route::post('/projects/delete', 'ProjectsController@delete');
Route::post('/projects/shop_search', 'ProjectsController@shop_search');
Route::post('/product_labels/edit', 'ProductLabelsController@edit');
Route::post('/product_labels/delete', 'ProductLabelsController@delete');
Route::post('/product_labels/id_list', 'ProductLabelsController@id_list');
Route::post('/product_labels/delete_id', 'ProductLabelsController@delete_id');
Route::post('/products/set_many_onsale', 'ProductsController@set_many_onsale');
Route::post('/products/copyProduct', 'ProductsController@copyProduct');
Route::post('/products/shop_search', 'ProductsController@shop_search');
Route::post('/products/create', 'ProductsController@create');
Route::post('/products/create_product', 'ProductsController@create_product');
Route::post('/products/edit', 'ProductsController@edit');
Route::post('/products/shop_get', 'ProductsController@shop_get');
Route::post('/products/shop_delete', 'ProductsController@shop_delete');
Route::post('/orders/export_excel', 'OrdersController@export_excel');
Route::post('/orders/selected_create', 'OrdersController@selected_create');
Route::post('/orders/edit', 'OrdersController@edit');
Route::post('/orders/batch_create', 'OrdersController@batch_create');
Route::post('/orders/searchByUserId', 'OrdersController@searchByUserId');
Route::post('/orders/shop_search', 'OrdersController@shop_search');
Route::post('/orders/shop_get', 'OrdersController@shop_get');
Route::post('/purchased/batch_set_cer_year', 'PurchasedController@batch_set_cer_year');
Route::post('/purchased/shop_search_pay_all', 'PurchasedController@shop_search_pay_all');
Route::post('/purchased/shop_search', 'PurchasedController@shop_search');
Route::post('/purchased/updateCerYears', 'PurchasedController@updateCerYears');
Route::post('/purchased/batch_set_rate', 'PurchasedController@batch_set_rate');
Route::post('/purchased/czUpload', 'PurchasedController@czUpload');
Route::post('/purchased/get_image', 'PurchasedController@get_image');
Route::post('/cers/shop_create', 'CersController@shop_create');
Route::post('/cers/shop_search', 'CersController@shop_search');
Route::post('/sections/create', 'SectionsController@create');
Route::post('/sections/shop_search', 'SectionsController@shop_search');
Route::post('/sections/shop_edit', 'SectionsController@shop_edit');
Route::post('/sections/shop_get', 'SectionsController@shop_get');
Route::post('/sections/shop_delete', 'SectionsController@shop_delete');
Route::post('/section_products/create', 'SectionProductsController@create');
Route::post('/section_products/delete', 'SectionProductsController@delete');
Route::post('/instructor/create', 'InstructorController@create');
Route::post('/instructor/search', 'InstructorController@search');
Route::post('/instructor/delete', 'InstructorController@delete');
Route::post('/class/create', 'ClassController@create');
Route::post('/class/edit', 'ClassController@edit');
Route::post('/class/search_shop', 'ClassController@search_shop');
Route::post('/class/search', 'ClassController@search');
Route::post('/class/delete', 'ClassController@delete');
Route::post('/class/autoCreate', 'ClassController@autoCreate');
Route::post('/class_users/create', 'ClassUsersController@create');
Route::post('/class_users/delete', 'ClassUsersController@delete');
Route::post('/class_users/create_all', 'ClassUsersController@create_all');
Route::post('/file/upload_banner', 'FileController@upload_banner');
Route::post('/file/upload_img', 'FileController@upload_img');
Route::post('/file/upload_excel', 'FileController@upload_excel');
Route::post('/file/upload_music', 'FileController@upload_music');
Route::post('/file/upload_work', 'FileController@upload_work');
Route::post('/file/upload_class', 'FileController@upload_class');
Route::post('/file/import_users', 'FileController@import_users');
Route::post('/file/import_products', 'FileController@import_products');
Route::post('/selected/search_group_by_user', 'SelectedController@search_group_by_user');
Route::post('/selected/search_group_by_product', 'SelectedController@search_group_by_product');
Route::post('/selected/search', 'SelectedController@search');
Route::post('/selected/update', 'SelectedController@update');
Route::post('/selected/delete', 'SelectedController@delete');
Route::post('/selected/create', 'SelectedController@create');
Route::post('/selected/create_by_idcard', 'SelectedController@create_by_idcard');
Route::post('/selected_batch/search', 'SelectedBatchController@search');
Route::post('/selected_batch/edit', 'SelectedBatchController@edit');
Route::post('/selected_batch/create', 'SelectedBatchController@create');
Route::post('/discuss_theme/delete', 'DiscussThemeController@delete');
Route::post('/discuss_theme/create', 'DiscussThemeController@create');
Route::post('/discuss_theme/search', 'DiscussThemeController@search');
Route::post('/discuss_theme/get', 'DiscussThemeController@get');
Route::post('/discuss_theme/edit', 'DiscussThemeController@edit');
Route::post('/banner/search_not_id', 'BannerController@search_not_id');
Route::post('/banner/create', 'BannerController@create');
Route::post('/banner/update', 'BannerController@update');
Route::post('/banner/delete', 'BannerController@delete');
Route::post('/product_news/search', 'ProductNewsController@search');
Route::post('/product_news/insert', 'ProductNewsController@insert');
Route::post('/product_news/delete', 'ProductNewsController@delete');
Route::post('/product_news/update', 'ProductNewsController@update');
Route::post('/product_news/news', 'ProductNewsController@news');
Route::post('/product_notices/search', 'ProductNoticesController@search');
Route::post('/product_notices/insert', 'ProductNoticesController@insert');
Route::post('/product_notices/delete', 'ProductNoticesController@delete');
Route::post('/product_notices/update', 'ProductNoticesController@update');
Route::post('/product_notices/notice', 'ProductNoticesController@notice');
Route::post('/icon/search', 'IconController@search');
Route::post('/icon_type/search', 'IconTypeController@search');
Route::post('/package/shop_search', 'PackageController@shop_search');
Route::post('/package/shop_edit', 'PackageController@shop_edit');
Route::post('/package/shop_create', 'PackageController@shop_create');
Route::post('/package/shop_delete', 'PackageController@shop_delete');
Route::post('/package_product/shop_search', 'PackageProductController@shop_search');
Route::post('/package_product/shop_delete', 'PackageProductController@shop_delete');
Route::post('/package_product/shop_create', 'PackageProductController@shop_create');
Route::post('/package_section/shop_create', 'PackageSectionController@shop_create');
Route::post('/package_section/shop_delete', 'PackageSectionController@shop_delete');
Route::post('/package_section/shop_search', 'PackageSectionController@shop_search');
Route::post('/attribute_value/shop_create_value', 'AttributeValueController@shop_create_value');
Route::post('/attribute_value/shop_edit', 'AttributeValueController@shop_edit');
Route::post('/attribute_value/shop_delete', 'AttributeValueController@shop_delete');
Route::post('/attribute/shop_search', 'AttributeController@shop_search');
Route::post('/attribute/shop_edit', 'AttributeController@shop_edit');
Route::post('/attribute/shop_delete', 'AttributeController@shop_delete');
Route::post('/attribute/shop_create', 'AttributeController@shop_create');
Route::post('/attribute/shop_get_value', 'AttributeController@shop_get_value');
Route::post('/package_attribute_value/search', 'PackageAttributeValueController@search');
Route::post('/package_attribute_value/shop_search', 'PackageAttributeValueController@shop_search');
Route::post('/package_attribute_value/shop_delete', 'PackageAttributeValueController@shop_delete');
Route::post('/package_attribute_value/shop_create', 'PackageAttributeValueController@shop_create');
Route::post('/attribute_value_type/shop_delete', 'AttributeValueTypeController@shop_delete');
Route::post('/navigation_bar/shop_search', 'NavigationBarController@shop_search');
Route::post('/navigation_bar/shop_create', 'NavigationBarController@shop_create');
Route::post('/navigation_bar/shop_edit', 'NavigationBarController@shop_edit');
Route::post('/navigation_bar/shop_delete', 'NavigationBarController@shop_delete');
});
Route::group(["middleware" => "client"], function(){
Route::post('/users/profile', 'UsersController@profile');
Route::post('/users/edit', 'UsersController@edit');
Route::post('/users/setpswd', 'UsersController@setpswd');
Route::post('/users/isPay', 'UsersController@isPay');
Route::post('/users/balance', 'UsersController@balance');
Route::post('/users/id_arr_balance', 'UsersController@id_arr_balance');
Route::post('/users/idcard_arr_balance', 'UsersController@idcard_arr_balance');
Route::post('/users/balance_token', 'UsersController@balance_token');
Route::post('/users/user_by_idcard', 'UsersController@user_by_idcard');
Route::post('/users/idcard_arr_id', 'UsersController@idcard_arr_id');
Route::post('/resources/get', 'ResourcesController@get');
Route::post('/activities/get', 'ActivitiesController@get');
Route::post('/project_tasks/search_group_by_task', 'ProjectTasksController@search_group_by_task');
Route::post('/project_tasks/search', 'ProjectTasksController@search');
Route::post('/project_tasks/search_no_activity', 'ProjectTasksController@search_no_activity');
Route::post('/shopping_cart/create', 'ShoppingCartController@create');
Route::post('/shopping_cart/search', 'ShoppingCartController@search');
Route::post('/shopping_cart/edit', 'ShoppingCartController@edit');
Route::post('/shopping_cart/delete', 'ShoppingCartController@delete');
Route::post('/orders/create', 'OrdersController@create');
Route::post('/orders/alipay', 'OrdersController@alipay');
Route::post('/orders/wxpay', 'OrdersController@wxpay');
Route::post('/orders/wxpay_js', 'OrdersController@wxpay_js');
Route::post('/orders/search', 'OrdersController@search');
Route::post('/orders/searchCourses', 'OrdersController@searchCourses');
Route::post('/orders/cancel', 'OrdersController@cancel');
Route::post('/orders/get', 'OrdersController@get');
Route::post('/orders/edit_state', 'OrdersController@edit_state');
Route::post('/purchased/search_by_userId_and_packageId', 'PurchasedController@search_by_userId_and_packageId');
Route::post('/purchased/get_rate_by_userid', 'PurchasedController@get_rate_by_userid');
Route::post('/purchased/search', 'PurchasedController@search');
Route::post('/purchased/search_pay_all', 'PurchasedController@search_pay_all');
Route::post('/purchased/search_pay_all_type_two', 'PurchasedController@search_pay_all_type_two');
Route::post('/purchased/sumbitResult', 'PurchasedController@sumbitResult');
Route::post('/purchased/sumbitRate', 'PurchasedController@sumbitRate');
Route::post('/purchased/updateCerYear', 'PurchasedController@updateCerYear');
Route::post('/purchased/get_more', 'PurchasedController@get_more');
Route::post('/purchased/get', 'PurchasedController@get');
Route::post('/cers/create', 'CersController@create');
Route::post('/cers/search', 'CersController@search');
Route::post('/instructor/edit', 'InstructorController@edit');
Route::post('/instructor/setpswd', 'InstructorController@setpswd');
Route::post('/instructor/get', 'InstructorController@get');
Route::post('/instructor/profile', 'InstructorController@profile');
Route::post('/class/instructor_search', 'ClassController@instructor_search');
Route::post('/class/get', 'ClassController@get');
Route::post('/class/get_rate_by_user_id_and_class_id', 'ClassController@get_rate_by_user_id_and_class_id');
Route::post('/evaluates/create', 'EvaluatesController@create');
Route::post('/evaluates/edit', 'EvaluatesController@edit');
Route::post('/class_users/search', 'ClassUsersController@search');
Route::post('/class_users/get_class_by_purchased_id', 'ClassUsersController@get_class_by_purchased_id');
Route::post('/class_bulletin/create', 'ClassBulletinController@create');
Route::post('/class_bulletin/delete', 'ClassBulletinController@delete');
Route::post('/class_bulletin/edit', 'ClassBulletinController@edit');
Route::post('/class_file/create', 'ClassFileController@create');
Route::post('/class_file/delete', 'ClassFileController@delete');
Route::post('/search_history/search', 'SearchHistoryController@search');
Route::post('/search_history/create', 'SearchHistoryController@create');
Route::post('/search_history/delete_all', 'SearchHistoryController@delete_all');
Route::post('/search_history/delete', 'SearchHistoryController@delete');
Route::post('/discuss_theme/get_with_comment', 'DiscussThemeController@get_with_comment');
Route::post('/comment/create', 'CommentController@create');
Route::post('/comment/get_comment_by_theme', 'CommentController@get_comment_by_theme');
Route::post('/private/create', 'PrivateController@create');
Route::post('/private/get', 'PrivateController@get');
Route::post('/private/get_by_purchased_id', 'PrivateController@get_by_purchased_id');
Route::post('/answ_question/create', 'AnswQuestionController@create');
Route::post('/answ_question/search', 'AnswQuestionController@search');
Route::post('/answ_question_comment/search', 'AnswQuestionCommentController@search');
Route::post('/answ_question_comment/create', 'AnswQuestionCommentController@create');
});
Route::group(["middleware" => "admin"], function(){
Route::post('/shops/search', 'ShopsController@search');
Route::post('/shops/create', 'ShopsController@create');
Route::post('/orders/searchAdmin', 'OrdersController@searchAdmin');
Route::post('/evaluates/delete', 'EvaluatesController@delete');
});
