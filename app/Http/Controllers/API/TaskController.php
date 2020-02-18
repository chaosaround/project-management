<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskController extends Controller
{
    /**
     * Display a listing of the Tasks
     * sorted by priority field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json\ResourceCollection
     */
    public function index(Request $request)
    {
    	if ($request->has('project_id')) {
			$project = Project::findOrFail($request->project_id);
		    return TaskResource::collection($project->tasks()->orderBy('priority')->get());
	    }
	    return TaskResource::collection(Task::orderBy('priority')->get());
    }

    /**
     * Store a newly created Task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json\JsonResource
     */
    public function store(Request $request)
    {
	    $request->validate([
		    'name' => 'required|max:255',
		    'project_id' => 'nullable|exists:App\Project,id'
	    ]);

	    $data = array_merge($request->only(['name', 'project_id']), ['priority' => Task::maxPriority() + 1]);

	    $task = Task::create($data);
	    return new TaskResource($task);
    }

    /**
     * Display the specified Task.
     *
     * @param  \App\Task  $task
     * @return Json\JsonResource
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified Task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return Json\JsonResource
     */
    public function update(Request $request, Task $task)
    {
	    $request->validate([
		    'name' => 'max:255',
		    'project_id' => 'nullable|exists:App\Project,id'
	    ]);

	    $task->update($request->only(['name', 'project_id']));
	    return new TaskResource($task);
    }

    /**
     * Remove the specified Task.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     * @throws \Exception if Model has primary key issue
     */
    public function destroy(Task $task)
    {
	    $task->delete();
	    return response()->json(null, 204);
    }

	/**
	 * Move task before or after the referenced task,
	 * reorder priority field
	 *
	 * @param Request $request
	 * @param int $task_id
	 *
	 * @return Json\JsonResource
	 */
	public function move(Request $request, $task_id)
	{
		$request->validate([
			'type' => 'in:before,after'
		]);

		$task = Task::findOrFail($task_id);
		$reference = Task::findOrFail($request->reference_id);
		$type = $request->type;

		if ($task->id === $reference->id) {
			throw new BadRequestHttpException("Wrong task ID's, can't be equal!");
		}

		$direction = $task->priority > $reference->priority ? "up" : "down";

		if ($direction === "up") {
			$start = $reference->priority;
			if ($type === "after") {
				$start++;
			}

			$end = $task->priority - 1;

			DB::transaction(function() use ($start, $end, $task) {
				Task::whereBetween('priority', [$start, $end])->increment('priority');
				$task->update(['priority' => $start]);
			});

		}
		else {
			// down
			$start = $task->priority + 1;

			$end = $reference->priority;
			if ($type === "before") {
				$end--;
			}

			DB::transaction(function() use ($start, $end, $task) {
				Task::whereBetween('priority', [$start, $end])->decrement('priority');
				$task->update(['priority' => $end]);
			});
		}

		return new TaskResource($task);
	}
}
