<?php

//use Illuminate\Http\Request;
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::apiResources([
	'projects' => 'API\ProjectController',
	'tasks' => 'API\TaskController'
]);

Route::put('tasks/{task_id}/move', 'API\TaskController@move');