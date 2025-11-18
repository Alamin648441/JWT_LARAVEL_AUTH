<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SoftDeleteController extends Controller
{
    use ApiResponse;

    public function index(){
        $users = User::all();
        return $this->success($users,"successfully get data",200);
    }

    public function UserDelete($id){
        $user = User::find($id);
        if(!$user){
            return $this->error("User not found", 404);
        }
        $user->delete();
        return $this->success($user,"successfully delete",200);
    }

    public function withTrashedData(){
        $users = User::withTrashed()->get();
        return $this->success($users,"trashed user",200);
    }

    public function onlyTrashed(){
        $users = User::onlyTrashed()->get();
        return $this->success($users,"success",200);
    }

    public function restore($id){
        $user = User::withTrashed()->find($id);
        if(!$user){
            return $this->error("User not found", 404);
        }
        $user->restore();
        return $this->success($user,"restore user",200);
    }
}
