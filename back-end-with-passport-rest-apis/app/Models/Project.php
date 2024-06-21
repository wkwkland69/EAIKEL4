<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Project extends Model
{
    protected $table = "projects";
    protected $primaryKey = "id";
    public $incrementing = false;

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProject()
    {
        try {
            $result = $this->where('user_id',Auth::user()->id)->get();
            if (count($result) > 0) {
                return $result;
            }
            return null;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["getProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return null;
        }
    }

    public function addProject($name, $budget, $responsible_user, $status, $user_id)
    {
        try {
            $this->name = $name;
            $this->budget = $budget;
            $this->user_id = $user_id;
            $this->responsible_user = $responsible_user;
            $this->status = $status;
            if ($this->save()) {
                return true;
            }
            return false;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["addProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return false;
        }
    }

    public function getSingleProject($id)
    {
        try {
            $result = $this->where([['id', $id],['user_id',Auth::user()->id]])->first();
            if (isset($result) & !empty($result)) {
                return $result;
            }
            return null;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["getSingleProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return null;
        }
    }

    public function updateProject($id, $name, $budget, $responsible_user, $status)
    {
        try {
            $result = $this->where('id', $id)
                ->update(['name' => $name, 'budget' => $budget, 'responsible_user' => $responsible_user, 'status' => $status]);
            if ($result) {
                return true;
            }
            return false;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["addProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return false;
        }
    }

    public function deleteProject($id)
    {
        try {
            $result = $this->where('id', $id)->delete();
            if ($result) {
                return true;
            }
            return false;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["deleteProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return false;
        }
    }
    public function getTotalProject()
    {
        try {
            $result = $this->where('user_id',Auth::user()->id)->count();
            if ($result > 0) {
                return $result;
            }
            return 0;
        } catch (QueryException $ex) {
            Log::info("TaskModel Error", ["getTotalProduct" => $ex->getMessage(), "line" => $ex->getLine()]);
            return 0;
        }
    }
    public function getCompleteProject()
    {
        try {
            $result = $this->where([['status',1],['user_id',Auth::user()->id]])
                            ->count();
            if ($result > 0) {
                return $result;
            }
            return 0;
        } catch (QueryException $ex) {
            Log::info("TaskModel Error", ["getTotalProduct" => $ex->getMessage(), "line" => $ex->getLine()]);
            return 0;
        }
    }
}
