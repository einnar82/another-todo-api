<?php

use App\Http\Controllers\API\TasksController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TasksController::class);
Route::get('/labels', [TasksController::class, 'getUniqueLabels']);
