<?php
/*
 * Dingo\Api\Routing\Router  $api
 */
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'middleware' => []], function ($api) {
    $api->get("algorithms", 'AlgorithmController@index')->name('algorithms.index');
});
