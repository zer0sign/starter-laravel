<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

use App\Models\User;


class PermissionsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $roles = Role::paginate(10);
        return view('dashboard.permissions.index')->with(compact('roles','user'));
    }

    public function rolePermissions($id){
        $user = Auth::user();
        $all = Permission::all();
        $role_permissions = Role::with('permissions')->where('id',$id)->first();
        return view('dashboard.permissions.rolePermissions')->with(compact('role_permissions','user','all'));
    }

    public function addRoles(){
        $user = Auth::user();
        return view('dashboard.permissions.add')->with(compact('user'));
    }

    public function storeRoles(Request $request){
        $validation = $request->validate([
            'nama_role' => 'required|unique:roles,name',
        ], [
            'nama_role.required' => 'Nama Role harus diisi.',
            'nama_role.unique' => 'Nama Role sudah digunakan.',
        ]);
        $nama_role = $request->nama_role;
        
        try {
            DB::transaction(function () use ($nama_role) {
                Role::create(['name' => $nama_role]);
                createLog(Auth::user()->username, 'add Roles');
            });
            Alert::success('Success', 'Berhasil Menambah Role');
            return redirect('/permissions');
        } catch (\Throwable $th) {
            dd($th);
            Alert::error('Error', 'Gagal Menambah Roles');
            return redirect('/permissions');
        }
        
    }

    public function changePermission(Request $request){
        $permissionName = $request->input('permission_name');
        $roleId = $request->input('role_id'); // Sesuaikan dengan cara Anda mendapatkan ID pengguna
    
        $role = Role::find($roleId);
    
        if (!$role) {
            return response()->json(['status' => 'error', 'message' => 'role not found'], 404);
        }
    
        if ($request->input('is_active') === 'true') {
            // Jika checkbox aktif, tambahkan permission
            $role->givePermissionTo($permissionName);
            return response()->json(['status' => 'aktif']);
        } else {
            // Jika checkbox tidak aktif, hapus permission
            $role->revokePermissionTo($permissionName);
            return response()->json(['status' => 'nonaktif']);
        }
    
    }

    public function changeRoles(Request $request){
        $rolesName = $request->input('rolesNames');
        $userId = $request->input('userId'); // Sesuaikan dengan cara Anda mendapatkan ID pengguna
    
        $user = User::find($userId);

    
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'user not found'], 404);
        }
    
        if ($request->input('is_active') === 'true') {
            
            $user->assignRole($rolesName);
            return response()->json(['status' => 'aktif']);
        } else {
            $user->removeRole($rolesName);
            return response()->json(['status' => 'nonaktif']);
        }
    }

}
