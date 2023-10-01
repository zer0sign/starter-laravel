<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index(){
        $activitys = ActivityLog::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.activity.index')->with(compact('activitys'));
    }
    public function search(Request $request){
        $q = $request->input('query');
        $results = ActivityLog::where('username', 'like', '%' . $q . '%')->orderBy('id', 'desc')->get();
        return response()->json(['data' => $results]);
    }
}
