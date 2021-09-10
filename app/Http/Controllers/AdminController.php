<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request){

        //First check if you are loggegin and admin user ....

        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }

        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        // //You are already logged in.. so check for if you are an admin user ...
        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }

        // return view('welcome');

        return $this->checkForPermission($user, $request);
    }

    public function checkForPermission($user,  $request)
    {
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if (!$permission) {
            return view('welcome');
        }

        foreach ($permission as $p) {
            if ($p->name == $request->path()) {
                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }
        if ($hasPermission) {
            return view('welcome');
        }

        // return view('welcome');
        return view('notfound');
    }



    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    //
    public function addCrypto(Request $request){
        $this->validate($request,[
            'tagName' => 'required'
        ]);
       return Lists::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editCrypto(Request $request){
        $this->validate($request,[
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Lists::where('id', $request->id)->update([
           'tagName' => $request->tagName,
        ]);

    }
    public function deleteCrypto(Request $request){
        echo 'ok';
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Lists::where('id', $request->id)->delete();

    }

    public function getCrypto(){
        return Lists::orderBy('id', 'desc')->get();
    }

    public function upload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }
    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        return $this->deleteFileFromServer($fileName, false);
        // return 'done';
    }
    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            $filePath = public_path() . $fileName;
        }
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return $filePath;
    }

    public function addCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
       return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function getCategory(Request $request){
        return Category::orderBy('id', 'desc')->get();
    }

    public function editCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function deleteCategory(Request $request){
        // first delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Category::where('id', $request->id)->delete();
    }

    public function createUser(Request $request){
        //validate request
        $this->validate($request,[
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);

        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }

    public function getUser(Request $request){
        return User::get();
    }

    public function editUser(Request $request){
        $this->validate($request,[
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'role_id' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }

    public function adminLogin(Request $request){
        // validate request
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();


            if ($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details',
            ], 401);
        }
    }

    public function addRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
        ]);

        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function getRoles(Request $request){
        return Role::all();
    }

    public function editRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
        ]);

        return Role::where('id',$request->id)->update([
            'roleName' => $request->roleName
        ]);


    }

    public function assignRoles(Request $request){
        $this->validate($request,[
            'permission' => 'required',
            'id' => 'required'
        ]);

        return Role::where('id',$request->id)->update([
            'permission' => $request->permission
        ]);
    }

    public function setAction(Request $request){
        //validate request
        $this->validate($request,[
            'clientId' => 'required',
            'coinName' => 'required',
            'quantity' => 'required',
            'actionType' => 'required',
            'cours' => 'required',
        ]);

        $action = Action::create([
            'clientId' => $request->clientId,
            'coinName' => $request->coinName,
            'quantity' => $request->quantity,
            'actionType' => $request->actionType,
            'cours' => $request->cours,
        ]);
        return $action;
    }

    public function getAction(){
        // return Action::all();
        return Action::orderBy('id', 'desc')->get();
    }

    public function updateWallet(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->update([
            'wallet' => $request->wallet,
         ]);
    }
}
