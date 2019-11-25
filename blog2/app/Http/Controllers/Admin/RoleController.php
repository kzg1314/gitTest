<?php

namespace App\Http\Controllers\Admin;

use App\Model\Permission;
use App\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    //获取授权页面
    public function auth($id)
    {
        //获取当前的角色
        $role = Role::find($id);
        //获取所有的权限列表
        $perms = Permission::get();

        //获取当前角色拥有的权限
        $own_perms = $role->permission;
//        dd($own_perms);

        //角色拥有的权限的id
        $own_pers = [];
        foreach ($own_perms as $v){
            $own_pers[] = $v->id;
        }

        return view('admin.role.auth',compact('role','perms','own_pers'));
    }

    //处理授权
    public function doAuth(Request $request)
    {
        $input = $request->except('_token');
//        dd($input);
        //删除当前角色已有的权限
        \DB::table('role_permission')->where('role_id',$input['role_id'])->delete();

        //添加新授予的权限
        if (!empty($input['permission_id'])){
            foreach ($input['permission_id'] as $v){
                \DB::table('role_permission')->insert(['role_id'=>$input['role_id'],'permission_id'=>$v]);
            }
        }

        return redirect('admin/role');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //1 获取数据库的所有数据
        $role = Role::get();

        // 2 返回一个列表页面
        return view('admin.role.list',compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //返回一个添加页面
        return view('admin.role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1 获取表单提交数据
        $input = $request->except('_token');
//        dd($input);


        //2 进行表单数据验证

        //3 将数据添加到数据库中
        $res = Role::create($input);

        //4 根据添加成功与否，给前端返回一个json格式的反馈
        if ($res){
            return redirect('admin/role');
        }else{
            return back()->with('msg','添加失败');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function edit($id)
    {
        $role = Role::find($id);
        return view('admin.role.edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //1 根据id获取要修改的记录
        $role = Role::find($id);
        //2 获取要修改成的用户名
        $rolename = $request->input('role_name');

        $role->role_name = $rolename;

        $res = $role->save();

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
    public function destroy($id)
    {
        $role = Role::find($id);
        $res = $role->delete();
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
        $res = Role::destroy($input);
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
