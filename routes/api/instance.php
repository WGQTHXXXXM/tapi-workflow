<?php
/*
 * Dingo\Api\Routing\Router  $api
 */
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'middleware' => []], function ($api) {

    $api->get("instances", 'InstanceController@index')->name('instances.index');
    $api->post("instances", 'InstanceController@store')->name('instances.store');
    $api->get("instances/{id}", 'InstanceController@show')->name('instances.show');
    $api->delete("instances/{id}", 'InstanceController@delete')->name('instances.delete');

    $api->post("instances/{id}/decision", 'InstanceController@decision')->name('instances.decision');
    $api->get("instances/{id}/records", 'InstanceController@records')->name('instances.records');

    //返回实例start的任务
    $api->get("instances/{id}/curtask", 'InstanceController@curInsTaskStart')->name('instances.curInsTaskStart');
    $api->put("instances/{id}", 'InstanceController@update')->name('instances.update');//启动实例

    //通过ids批量查询实例
    $api->post("instances/in_ids", 'InstanceController@getInstanceInIds')->name('instances.getInstanceInIds');

});
