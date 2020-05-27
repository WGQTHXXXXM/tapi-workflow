<?php
/*
 * Dingo\Api\Routing\Router  $api
 */
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'middleware' => []], function ($api) {

    $api->get("templates", 'TemplateController@index')->name('templates.index');
    $api->post("templates", 'TemplateController@store')->name('templates.store');
    $api->get("templates/{id}", 'TemplateController@show')->name('templates.show');
    $api->delete("templates/{id}", 'TemplateController@delete')->name('templates.delete');
    $api->put("templates/{id}", 'TemplateController@update')->name('templates.update');

    //锁定 解锁
    $api->put("templates/{id}/lock", 'TemplateController@lock')->name('templates.lock');
    $api->put("templates/{id}/unlock", 'TemplateController@unlock')->name('templates.unlock');


    $api->post('templates/{id}/task','TemplateController@createTask')->name('templates.createTask');
    $api->put('templates/tasks/{id}','TemplateController@updateTask')->name('templates.updateTask');
    $api->delete('templates/tasks/{id}','TemplateController@removeTask')->name('templates.removeTask');


    $api->post('templates/{id}/decision','TemplateController@createDecision')->name('templates.createDecision');
    $api->put('templates/decisions/{id}','TemplateController@updateDecision')->name('templates.updateDecision');
    $api->delete('templates/decisions/{id}','TemplateController@removeDecision')->name('templates.removeDecision');



    $api->post('templates/{id}/line','TemplateController@createLine')->name('templates.createLine');
    $api->put('templates/lines/{id}','TemplateController@updateLine')->name('templates.updateLine');
    $api->delete('templates/lines/{id}','TemplateController@removeLine')->name('templates.removeLine');



});
