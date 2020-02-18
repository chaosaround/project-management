<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json;
use App\Http\Controllers\Controller;
use App\Project;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of Projects.
     *
     * @return Json\ResourceCollection
     */
    public function index()
    {
	    return ProjectResource::collection(Project::all());
    }

    /**
     * Store a newly created Project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json\JsonResource
     */
    public function store(Request $request)
    {
	    $request->validate([
		    'name' => 'required|max:255'
	    ]);

        $project = Project::create($request->only(['name']));
        return new ProjectResource($project);
    }

    /**
     * Display the specified Project.
     *
     * @param  \App\Project  $project
     * @return Json\JsonResource
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    /**
     * Update the specified Project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return Json\JsonResource
     */
    public function update(Request $request, Project $project)
    {
	    $request->validate([
		    'name' => 'required|max:255'
	    ]);

	    $project->update($request->only(['name']));
	    return new ProjectResource($project);
    }

	/**
	 * Remove the specified Project.
	 *
	 * @param  \App\Project $project
	 * @return \Illuminate\Http\Response
	 * @throws \Exception if Model has primary key issue
	 */
    public function destroy(Project $project)
    {
	    $project->delete();
	    return response()->json(null, 204);
    }
}
