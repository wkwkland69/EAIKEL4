<?php


namespace App\Classes;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskClass
{
    private $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        try {
            $userId = Auth::id();
            $data = $this->task->getTask($userId);
            if (isset($data) && !empty($data)) {
                return response()->json(['status' => true, 'message' => 'task get success', 'data' => $data])->setStatusCode(200);
            }
            return response()->json(['status' => false, 'message' => 'error while get task'])->setStatusCode(400);
        } catch (\Exception $ex) {
            Log::info("TaskClassClass Error", ["getTask" => $ex->getMessage(), "line" => $ex->getLine()]);
            return response()->json(['status' => false, 'message' => 'internal server error'])->setStatusCode(500);
        }
    }

    public function addTask($name, $due_date, $responsible_user, $status)
    {
        try {
            $userId = Auth::id();
            $addProduct = $this->task->addTask($userId, $name, $due_date, $responsible_user, $status);
            if ($addProduct) {
                return response()->json(['status' => true, 'message' => 'task add success'])->setStatusCode(200);
            }
            return response()->json(['status' => false, 'message' => 'error while add task'])->setStatusCode(400);
        } catch (\Exception $ex) {
            Log::info("TaskClass Error", ["addTask" => $ex->getMessage(), "line" => $ex->getLine()]);
            return response()->json(['status' => false, 'message' => 'internal server error'])->setStatusCode(500);
        }
    }

    // Sesuaikan method lainnya dengan penambahan user_id
}
