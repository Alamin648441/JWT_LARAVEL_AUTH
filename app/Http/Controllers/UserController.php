<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(User::all())
                ->addColumn('action', function ($project) {
                    return view('users.partials._actions', compact('project'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return abort(404);
    }
}
