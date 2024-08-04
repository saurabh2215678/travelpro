<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Hash;

class UserController extends Controller {

    /**
     * Users
     * URL: /admin/users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    protected $currentUrl;

    public function __construct(){
        $this->currentUrl = url()->current();
    }
    
    public function index() {
        $data = [];
        $roles = Role::all();
        $users = User::orderBy('created_at', 'desc')->get();

        $data['users'] = $users;
        $data['roles'] = $roles;

        return view('admin.users.index', $data);
    }


    public function add(Request $request, $user_id=0) {
        $data = [];

        $id = $request->id??'';

        if($request->method() == 'POST' || $request->method() == 'post'){
            //prd($request->toArray());

            if(is_numeric($user_id) && $user_id > 0){
                $this->validate($request, [
                    'email' => 'required|email|unique:users,email,' . $user_id,
                    'role' => 'required'
                ]);

                $updateData['email'] = $request->email;

                if(!empty($request->password)){
                    $updateData['password'] = bcrypt($request->password);
                }

                $is_updated = User::where('id', $user_id)->update($updateData);

                $description = json_encode($request);
                //prd($description);

                $function_name = $this->currentUrl;
                $action_table = 'users';
                $row_id = $id;
                $action_type = 'Edit On User';
                $action_description = 'Edit On('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;
            
            }
                if(isset($is_updated) && !empty($is_updated)){
                    $rolearray = array('role_id'=>$request->role);

                    $insertrole = array('user_id'=>$user_id,'role_id'=>$request->role);

                    $userRoleCount = DB::table('role_user')->where('user_id',$user_id)->get();
                    $userRole = DB::table('role_user')->where('user_id',$user_id);

                    if(count($userRoleCount)>0){
                        $userRole->update($rolearray);
                    }else{  
                        DB::table('role_user')->insert($insertrole);
                    }

                    // CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);


                    return redirect(route('admin.users.index'))->with('alert-success', 'Your user information has been updated successfully.');
                }

            else{
                $this->validate($request, [
                    'email' => 'required|max:255|email|unique:users',
                    'password' => 'sometimes|min:6|max:255',
                    'role' => 'required'
                ]);

                $insertData['email'] = $request->email;
                $insertData['password'] = bcrypt($request->password);

                $inserted = User::create($insertData);

                if($inserted && isset($inserted->id) && $inserted->id > 0){

                $description = json_encode($request);

                $function_name = $this->currentUrl;
                $action_table = 'users';
                $row_id = $id;
                $action_type = 'Add On User';
                $action_description = 'Add On ('.$request->name.")";
                $description = 'Add('.$request->name.") ".$description;

                DB::table('role_user')->insert(['user_id' => $inserted->id, 'role_id' => $request->role]);

                // CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);

                    return redirect(route('admin.users.index'))->with('alert-success', 'Your user information has been added successfully.');
                }
            }
        }

        $role_user = '';
        $user = '';

        if(is_numeric($user_id) && $user_id > 0){
            
            $role_user = DB::table('role_user')->where('user_id', $user_id)->first();

            $user = User::where('id', $user_id)->first();
        }

        $roles = Role::all();
        //pr($user_id);
        $users = User::orderBy('created_at', 'desc')->get();
        $data['roles'] = $roles;

        $page_heading = 'Add User';

        $data['page_heading'] = $page_heading;
        $data['user_id'] = $user_id;
        $data['user'] = $user;
        $data['role_user'] = $role_user;

        return view('admin.users.add', $data);
    }

    /**
     * Update User
     * URL: /admin/users/{user} (POST)
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $user) {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|max:255|email|unique:users,email,' . $user['id'],
            'password' => 'sometimes|min:6|max:255',
            'type' => 'required|in:user,admin'
        ]);

        foreach ([
                     'first_name',
                     'last_name',
                     'email',
                     'type',
                 ] as $field) {
            if (isset($data[$field]) && $data[$field] != $user->{$field}) {
                $user->{$field} = $data[$field];
            }
        }

        // Set the new password
        if (!empty($data['password'])) {
            $user->{'password'} = bcrypt($data['password']);
        }

        $user->save();

        return redirect(route('admin.users.show', $user['id']))->with('alert-success', 'Your user information has been updated successfully.');
    }

    public function change_password()
    {
        return view('admin.users.change_password');   
    }

    public function save_password(Request $request)
    {
        $validation = $request->validate([
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'new_password' => 'required|min:6|different:password',
                'confirm_new_password' => 'required|same:new_password',
            ]);

        $admin =  auth()->guard('admin')->user(); 
        $user_old_password = $admin->password; //$2y$10$uyipF/wYcZsfCyZYaqqRJ.ivnAfwyzSrXuoGDGx2VYcUTX1BtyVQ2
        if (!(Hash::check($request->password, $user_old_password))) 
        {
            return redirect()->back()->with("alert-danger","Your current password does not matches with the password you provided. Please try again.");
        }
       
        $user_id = $admin->id;
        $user = User::find($user_id);
        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect()->back()->with("alert-success","Password changed successfully !");
    }

    


}