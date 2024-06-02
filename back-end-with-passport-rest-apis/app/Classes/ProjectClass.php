<?php

namespace App\Classes;

use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProjectClass
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        try {
            $userId = Auth::id();
            $data = $this->project->getProject($userId);
            if (isset($data) && !empty($data)) {
                return response()->json(['status' => true, 'message' => 'project get success', 'data' => $data])->setStatusCode(200);
            }
            return response()->json(['status' => false, 'message' => 'error while get project'])->setStatusCode(400);
        } catch (\Exception $ex) {
            Log::info("TaskClassClass Error", ["getProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return response()->json(['status' => false, 'message' => 'internal server error'])->setStatusCode(500);
        }
    }

    public function addProject($name, $budget, $responsible_user, $status)
    {
        try {
            $userId = Auth::id();
            $addProduct = $this->project->addProject($userId, $name, $budget, $responsible_user, $status);
            if ($addProduct) {
                return response()->json(['status' => true, 'message' => 'project add success'])->setStatusCode(200);
            }
            return response()->json(['status' => false, 'message' => 'error while add project'])->setStatusCode(400);
        } catch (\Exception $ex) {
            Log::info("ProjectClass Error", ["addProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return response()->json(['status' => false, 'message' => 'internal server error'])->setStatusCode(500);
        }
    }

    // Sesuaikan method lainnya dengan penambahan user_id
}
