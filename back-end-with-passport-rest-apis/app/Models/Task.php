<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Task extends Model
{
    protected $table = "tasks";
    protected $primaryKey = "id";
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTask($userId)
    {
        try {
            $result = $this->where('user_id', $userId)->get();
            if (count($result) > 0) {
                return $result;
            }
            return null;
        } catch (QueryException $ex) {
            Log::info("TaskModel Error", ["getTask" => $ex->getMessage(), "line" => $ex->getLine()]);
            return null;
        }
    }

    public function addTask($userId, $name, $due_date, $responsible_user, $status)
    {
        try {
            $this->user_id = $userId;
            $this->name = $name;
            $this->due_date = $due_date;
            $this->responsible_user = $responsible_user;
            $this->status = $status;
            if ($this->save()) {
                return true;
            }
            return false;
        } catch (QueryException $ex) {
            Log::info("TaskModel Error", ["addTask" => $ex->getMessage(), "line" => $ex->getLine()]);
            return false;
        }
    }

    // Sesuaikan method lainnya dengan penambahan user_id
}
