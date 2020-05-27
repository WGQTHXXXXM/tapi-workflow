<?php
/*
 * Dingo\Api\Routing\Router  $api
 */
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'middleware' => []], function ($api) {

    $api->get("selects", 'SelectController@index')->name('selects.index');
    $api->post("selects", 'SelectController@store')->name('selects.store');
});
