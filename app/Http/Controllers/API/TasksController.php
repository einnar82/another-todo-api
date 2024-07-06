<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TaskRequest;
use App\Http\Resources\API\TaskResource;
use App\Models\Task;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

final class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection(Task::query()->with('labels')->simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): TaskResource
    {
        /** @var Task $task */
        $task = Task::query()->create(Arr::except($request->validated(), ['label_ids']));

        $task->labels()->attach($request->input('label_ids'));

        return new TaskResource($task->load('labels'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task->load('labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $task->update(Arr::except($request->validated(), ['label_ids']));

        $task->labels()->sync($request->input('label_ids'));

        return new TaskResource($task->load('labels'));
    }

    public function destroy(Task $task): Response
    {
        $task->labels()->detach();
        $task->delete();

        return response()->noContent();
    }
}
