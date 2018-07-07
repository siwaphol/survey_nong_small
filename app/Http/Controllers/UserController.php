<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Validation\Rule;
use App\Notifications\UserAddedNotification;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Session::get('user.level') == 'user'){
            return \Redirect::to('/');
        }
        if (\Session::get('user.level', 'admin') == 'admin') {
            $allUsers = User::orderBy('name')->get(['email', 'name', 'area', 'level', 'tel', 'id']);
        } else {
            $allUsers = User::whereArea(\Session::get('user.area'))
                ->where('name','not like','Admin')
                ->orderBy('name')->get(['email', 'name', 'area', 'level', 'tel', 'id']);
        }

        return view('user.index', compact('allUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Session::get('user.level') == 'user'){
            return \Redirect::to('/');
        }
        $level = [];
        $area = [];
        if (Session::get('user.level', 'admin') == 'admin') {
            $level['admin'] = "Admin";
        }
        $level['manager'] = "ผู้จัดการเขต";
        $level['user'] = "ผู้ใช้";
        $allArea = Area::all();
        foreach ($allArea as $a) {
            if (Session::get('user.level', 'admin') == 'admin') {
                $area[$a->id] = $a->name;
            } elseif (Session::get('user.level', 'admin') != 'admin' && $a->id == Session::get('user.area', '1')) {
                $area[$a->id] = $a->name;
            }
        }

        return view('user.create', compact('level', 'area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        //-------------gen password---------------
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#$-_';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($pass);

        Validator::make($request->input(), [
            'name' => 'required|max:255',
            //'password' => 'required',
            //'confirmPassword' => 'required|same:password',
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
            'tel' => 'required|max:20',
            'area' => 'required',
            'level' => 'required',
        ])->validate();
        $user->fill($request->input());
        $user->password = bcrypt($password);
        $user->save();
//        $user->notify(new UserAddedNotification($user, $password));
        return \Redirect::to('/user')->with('status', 'เพิ่มผู้ใช้เรียบร้อยแล้ว และส่งรหัสผ่านไปที่ ' . $user->email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $level = [];
        $area = [];
        if (Session::get('user.level', 'manager') == 'admin') {
            $level['admin'] = "Admin";
        }
        $level['manager'] = "ผู้จัดการเขต";
        $level['user'] = "ผู้ใช้";
        $allArea = Area::all();
        foreach ($allArea as $a) {
            if (Session::get('user.level', 'admin') == 'admin') {
                $area[$a->id] = $a->name;
            } elseif (Session::get('user.level', 'admin') != 'admin' && $a->id == Session::get('user.area', '1')) {
                $area[$a->id] = $a->name;
            }
        }
        return view('user.edit', compact('user', 'level', 'area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->input());
        $user = User::findOrFail($id);
        Validator::make($request->input(), [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'tel' => 'required|max:20',
            'area' => 'required',
            'level' => 'required',
        ])->validate();
        $user->fill($request->input());
        if ($request->input('newPassword') != "") {
            $user->password = bcrypt($request->input('newPassword'));
        }
        $user->save();
        return \Redirect::to('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return \Redirect::to('/user');
    }

    public function showChangePasswordForm()
    {
        return view('user.change_password');
    }

    public function changePassword(Request $request)
    {
        Validator::make($request->input(), [
            'password' => 'required',
            'new_password' => 'required|between:6,20',
            'confirm_new_password' => 'required|same:new_password',
        ])->validate();
        if (Hash::check($request->input('password'), \Auth::user()->password)) {
            $u = User::find(\Session::get('user.id'));
            $u->password = Hash::make($request->input('new_password'));
            $u->save();
            return \Redirect::back()->with('status', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
        } else {
            return \Redirect::back()->withErrors(['รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }
        // return \Redirect::to('/')->with('status', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }

}
