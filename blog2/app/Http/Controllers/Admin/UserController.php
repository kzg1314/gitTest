<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    //获取授权页面
    public function auth($id)
    {
        //获取当前用户
        $user = User::find($id);
        //获取所有的角色表
        $roles = Role::get();

        //获取当前用户所用有的角色
        $own_roles = $user->role;
//        dd($own_roles);
        //用户拥有的角色的id
        $own_role = [];
        foreach ($own_roles as $v){
            $own_role[] = $v->id;
        }

        return view('admin.user.auth',compact('user','roles','own_role'));
    }

    //处理授权
    public function doAuth(Request $request)
    {
        $input = $request->except('_token');
//        dd($input);

        //删除当前用户已有的角色
        \DB::table('user_role')->where('user_id',$input['user_id'])->delete();

        //添加新授权的角色
        if (!empty($input['role_id'])){
            foreach ($input['role_id'] as $v){
                \DB::table('user_role')->insert(['user_id'=>$input['user_id'],'role_id'=>$v]);
            }
        }

        return redirect('admin/user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //获取用户列表
    public function index(Request $request)
    {
        // 1 获取表单提交的数据
       $user = User::orderBy('user_id','asc')
           ->where(function ($query) use($request){
               $username = $request->input('username');
               $email = $request->input('email');
               if (!empty($username)){
                   $query->where('user_name','like','%'.$username.'%');
               }
               if (!empty($email)){
                   $query->where('email','like','%'.$email.'%');
               }
           })
           ->paginate($request->input('num')?$request->input('num'):3);

        return view('admin.user.list',compact('user','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //返回用户的添加页面
    public function create()
    {
        return view('admin.user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //执行添加操作
    public function store(Request $request)
    {

        //1 接受前台数据
        $input = $request->all();
//        return $input;


        //2 进行表单验证



        //3 添加到数据库
        $username = $input['username'];
        $pass = Crypt::encrypt($input['pass']);
        $email = $input['email'];

        $res = User::create(['user_name'=>$username,'user_pass'=>$pass,'email'=>$email]);


        //4 根据添加成功与否，给前端返回一个json格式的反馈
        if ($res){
            $data = [
                'status'=>0,
                'message'=>'添加成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'添加失败'
            ];
        }

        return $data;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //显示一条数据
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //返回一个修改页面
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //执行修改操作
    public function update(Request $request, $id)
    {
        //1 根据id获取要修改的记录
        $user = User::find($id);
        //2 获取要修改成的用户名
        $username = $request->input('user_name');
        $email = $request->input('email');

        $user->user_name = $username;
        $user->email = $email;

        $res = $user->save();

        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'修改成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'修改失败'
            ];
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //执行删除操作
    public function destroy($id)
    {
        $user = User::find($id);
        $res = $user->delete();
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'删除失败'
            ];
        }
        return $data;
    }

    //执行批量删除操作
    public function delAll(Request $request)
    {
        $input = $request->input('ids');
        $res = User::destroy($input);
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'删除失败'
            ];
        }
        return $data;
    }
}
