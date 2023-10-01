<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport; // Create this import class
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Validator;


use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(){    
        $users = User::with('roles')->paginate(10);
        $user = Auth::user();
        return view('dashboard.users.index')->with(compact('users','user'));
    }

    // Login
    public function login(Request $request){
        $credentials = $request->only('username', 'password');
        $remember_me = $request->has('remember_me') ? true : false; 
        if (Auth::attempt($credentials,$remember_me)) {
            createLog($request->username,'Login');
            return redirect()->intended('home');
        }else{
            return redirect()->back()->with('errors', 'Username atau Password Salah');
        }
    }
    

    // Logout
    public function logout(){
        $user = Auth::user();
        createLog($user->username,'Logout');
        Auth::logout();
        return redirect()->route('login');
    }

    public function add(){
        $user = Auth::user();
        return view('dashboard.users.add')->with(compact('user'));
    }
    public function edit($id){
        $user = Auth::user();
        $data = User::with('roles')->where('id',$id)->first();
        $roles = Role::all();
        return view('dashboard.users.edit')->with(compact('user','data','roles'));
    }

    public function saveEdit(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:username',
            'nama' => 'required',
        ]);
        $user = User::find($id);

        try {
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->save();
            Alert::success('Success', 'Berhasil Edit Data!');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal Edit Data!');
            return redirect()->back();
        }

    }


    public function importExcel(Request $request)
    {
        $validation = $request->validate([
            'file' => 'required|mimes:xls,xlsx', // Ensure the file is of type xls or xlsx
        ]);
        
        try {
            $file = $request->file('file');
            Excel::import(new UsersImport, $file);
            createLog(Auth::user()->username,'Import User');
            Alert::success('Success', 'Berhasil import');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal Import, Ada Duplikasi Username atau File Rusak!');
            return redirect()->back();
        }
    }

    public function loginAs($id){
        $user = Auth::loginUsingId($id);
        if($user){
            createLog(Auth::user()->username,'Login As '.$id);
            Alert::success('Success', 'Login sebagai akun lain Berhasil!');
            return redirect()->intended('home');
        }else{
            Alert::error('Error', 'Login sebagai akun lain gagal!')->persistent(true, true);
            return redirect()->back();
        }
    }

    public function templatetExcel(){
        $filename = 'Template-users-import.xlsx';
        $path = Storage::path($filename);
    if (!Storage::exists($filename) || !file_exists($path)) {
        // Handle the case where the file does not exist
        abort(404);
    }

    return response()->download($path, $filename, [], 'inline');
    }

    public function search(Request $request){
        $q = $request->input('query');
        $results = User::where('nama', 'like', '%' . $q . '%')->with('roles')->get();
        return response()->json(['data' => $results]);
    }

    public function destroy($id){
        $user = User::find($id);
        try {
            $user->delete();
            createLog(Auth::user()->username,'Delete User');
            Alert::success('Success', 'Berhasil Menghapus User!');
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error('Error', 'Gagal Menghapus User!');
            return redirect()->back();
        }
    }

}
