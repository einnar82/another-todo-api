<?php

namespace App\Http\Controllers\API;

use App\Filters\FilterByLabel;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TaskRequest;
use App\Http\Resources\API\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

final class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        /** @var \Illuminate\Database\Eloquent\Builder $tasks */
        $tasks = app(Pipeline::class)
            ->send(Task::query())
            ->through([
                FilterByLabel::class
            ])
            ->thenReturn();

        $query = $tasks->simplePaginate(9);

        return response()->json([
            'data' => $query->items(),
            'hasMorePages' => $query->hasMorePages(),
            'currentPage' => $query->currentPage()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): TaskResource
    {
        /** @var Task $task */
        $task = Task::query()->create($request->validated());

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }

    public function getUniqueLabels(): JsonResponse
    {
        $data = DB::table('tasks')
            ->select('labels')
            ->distinct()
            ->get()
            ->pluck('labels')
            ->flatten()
            ->unique()
            ->toArray();

        $decodedData = array_map(function($item) {
            return json_decode($item, true);
        }, $data);

        // Optionally flatten the array if you have nested arrays
        $flattenedData = array_merge(...$decodedData);

        // Get unique values
        $uniqueData = \array_values(array_unique($flattenedData));

        return \response()->json($uniqueData);
    }
}
