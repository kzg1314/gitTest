<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //添加方法
    // 获取一个添加页面
    public function  add()
    {
        return view('user/add');
    }

    //执行添加操作
    public function store(Request $request)
    {
        //1.获取客户端提交的数据
        $input = $request->except('_token');
        //dd($input);
        $input['pwd'] = md5($input['pwd']);

        //2.表单验证

        //3.添加操作
        $res = User::create($input);
        //4.如果添加成功，跳转到列表页，如果添加失败，跳转到原页面
        if ($res) {
            return redirect('user/index');
        }else{
            return back();
        }
    }

    //用户列表页
    public function index()
    {
        //获取用户数据
        $user = User::get();

        //返回用户列表
        return view('user/list',compact('user'));
    }

    //用户修改页面
    public function edit($id)
    {
        //1 根据id，找到修改的用户
        $user = User::find($id);

        //2 但会用户修改页面
        return view('user/edit',compact('user'));
    }

    //执行用户修改
    public function doEdit(Request $request)
    {
        //1.获取客户端提交的数据
        $input = $request->except('_token');
        //dd($input);
        $user = User::find($input['id']);
        //将提交过来的用户名替换原来的用户名
        $res = $user->update(['name'=>$input['name']]);
        //判断是否修改成功，跳转到不同的页面
        if ($res) {
            return redirect('user/index');
        }else{
            return back();
        }
    }

    //执行用户删除
    public function del($id)
    {
        //1 根据id，找到修改的用户
        $user = User::find($id);

        //2 执行删除操作
        $res = $user->delete();
        //判断是否删除成功，跳转到不同的页面
        if ($res) {
            return back();
        }else{
            return back();
        }
    }
}
