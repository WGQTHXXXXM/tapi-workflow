<?php
/*
 * Dingo\Api\Routing\Router  $api
 */
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'middleware' => []], function ($api) {


    $api->get("tasks/{id}/participants", 'TaskController@showParticipants')->name('tasks.showParticipants');
    $api->post("tasks/{id}/participant", 'TaskController@addParticipant')->name('tasks.addParticipant');
    $api->delete("tasks/participants/{id}", 'TaskController@removeParticipant')->name('tasks.removeParticipant');


    $api->put("tasks/{id}/attributes", 'TaskController@updateAttributes')->name('tasks.updateAttributes');
    $api->get("tasks/{id}", 'TaskController@show')->name('tasks.show');

    //任务的审批

    $api->post("tasks/{id}/decision", 'TaskController@decision')->name('tasks.decision');

});
