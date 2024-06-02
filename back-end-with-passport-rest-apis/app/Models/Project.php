<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Project extends Model
{
    protected $table = "projects";
    protected $primaryKey = "id";
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProject($userId)
    {
        try {
            $result = $this->where('user_id', $userId)->get();
            if (count($result) > 0) {
                return $result;
            }
            return null;
        } catch (QueryException $ex) {
            Log::info("ProjectModel Error", ["getProject" => $ex->getMessage(), "line" => $ex->getLine()]);
            return null;
        }
    }

    public function addProject($userId, $name, $budget, $responsible_user, $status)
    {
        try {
            $this->user_id = $userId;
            $this->name = $name;
            $this->budget = $budget;
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

}
