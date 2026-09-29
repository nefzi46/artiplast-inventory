<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    public function AllPermission(){
        $permissions = Permission::all();
        return view('admin.backend.pages.permission.all_permission', compact('permissions'));

    }
    //End method
    public function AddPermission(){
        $permissions = Permission::all();
        return view('admin.backend.pages.permission.add_permission', compact('permissions'));

    }
    //End method
    public function StorePermission(Request $request){
        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
        $notification = array(
            'message' => 'Permission Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }
    //End method
    public function EditPermission($id){
        $permissions = Permission::findOrFail($id);
        return view('admin.backend.pages.permission.edit_permission', compact('permissions'));
    }
    //End method

    public function UpdatePermission(Request $request){
        $per_id = $request->id;
        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }
    //End method
    public function DeletePermission($id){
        Permission::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }
    //End method

   ///////////////////////Roles///////////////////////////

   
    public function AllRole(){
        $roles = Role::all();
        return view('admin.backend.pages.role.all_role', compact('roles'));

    }
    //End method
    public function AddRole(){
        return view('admin.backend.pages.role.add_role');

    }
    //End method
    public function StoreRole(Request $request){
        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        $notification = array(
            'message' => 'Role Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }
    //End method
    public function EditRole($id){
        $roles = Role::findOrFail($id);
        return view('admin.backend.pages.role.edit_role', compact('roles'));
    }
    //End method

    public function UpdateRole(Request $request){
        $role_id = $request->id;
        Role::findOrFail($role_id)->update([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }
    //End method
    public function DeleteRole($id){
        Role::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }


    //////////////////////Add Role in Permission All methods//////////////////////
    public function AddRolesPermission(){
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();  //user model e getPermissionByGroupName nije banano method
        return view('admin.backend.pages.rolesetup.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    }
    //End method

     public function RolePermissionStore(Request $request){

        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } // End Foreach

        

        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
         ); 
         return redirect()->route('all.roles.permission')->with($notification); 

     }
      // End Method

      public function AllRolesPermission(){
        $roles = Role::all();
        return view('admin.backend.pages.rolesetup.all_roles_permission', compact('roles'));
      }
      // End Method

        public function AdminEditRolesPermission($id){
            $role = Role::findOrFail($id);
            $permissions = Permission::all();
            $permission_groups = User::getPermissionGroups();
            return view('admin.backend.pages.rolesetup.edit_roles_permission', compact('role', 'permissions', 'permission_groups'));
        }
        // End Method

        public function AdminRolesUpdate(Request $request, $id){
            $role = Role::findOrFail($id);
            $permissions = $request->permission;

            if (!empty($permissions)) {
                $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray(); //permission theke selected id gular name tultese
                $role->syncPermissions($permissionNames);                       //then role er sathe oi name er permission gular sync kortese
                                                                                //permission names are needed because thats how spatie's syncPermission method works
            }else{
                $role->syncPermissions([]); // Remove all permissions if none are selected
            }

            $notification = array(
                'message' => 'Role Permission Updated Successfully',
                'alert-type' => 'success'
             ); 
             return redirect()->route('all.roles.permission')->with($notification); 
        }
        // End Method

        public function AdminRolesDelete($id){
            $role = Role::findOrFail($id);
            $role->syncPermissions([]); // Remove all permissions associated with the role

            $role->delete(); // Delete the role itself

            $notification = array(
                'message' => 'Role and its Permissions Deleted Successfully',
                'alert-type' => 'success'
             ); 
             return redirect()->route('all.roles.permission')->with($notification); 
        }

        ////////////////////////////All Admin User Method//////////////////////

        
        public function AllAdmin(){
            $alladmin = User::where('role', 'admin')->get();
            return view('admin.backend.pages.admin.all_admin', compact('alladmin'));
        }
        // End Method

        public function AddAdmin(){
            $roles= Role::all();
            return view('admin.backend.pages.admin.add_admin', compact('roles'));
        }
        // End Method

        public function StoreAdmin(Request $request){

            // Check if user with this email already exists and delete if found
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                // Detach all roles before deleting
                $existingUser->roles()->detach();
                // Delete the existing user
                $existingUser->delete();
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'admin';
            $user->save();

            if ($request->roles) {
                $role = Role::where('id',$request->roles)->where('guard_name','web')->first();
                if ($role) {
                    $user->assignRole($role->name);
                }
            }

            $notification = array(
                'message' => 'New Admin Inserted Successfully',
                'alert-type' => 'success'
             ); 
             return redirect()->route('all.admin')->with($notification); 

        }
         // End Method

        public function EditAdmin($id){
            $admin = User::find($id);
            $roles = Role::all();
            return view('admin.backend.pages.admin.edit_admin',compact('admin','roles')); 
        }
        // End Method

        public function UpdateAdmin(Request $request,$id){

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email; 
            $user->role = 'admin';
            $user->save();
            
            $user->roles()->detach();

            if ($request->roles) {
                $role = Role::where('id',$request->roles)->where('guard_name','web')->first();
                if ($role) {
                    $user->assignRole($role->name);
                }
            }

            $notification = array(
                'message' => 'New Admin Updated Successfully',
                'alert-type' => 'success'
             ); 
             return redirect()->route('all.admin')->with($notification); 

        }
         // End Method

        public function DeleteAdmin($id){

            $admin = User::find($id);
            if (!is_null($admin)) {
                $admin->delete();
            }

            $notification = array(
                'message' => ' Admin Deleted Successfully',
                'alert-type' => 'success'
             ); 
             return redirect()->back()->with($notification); 

        }
         // End Method

}