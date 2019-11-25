<?php

namespace App\Http\Controllers\Admin;

use App\Model\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //1 获取数据库里的数据
        $permission = Permission::get();

        //2 返回一个列表页面
        return view('admin.permission.list',compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permission.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //接受前台的数据
        $input = $request->except('_token');

//        dd($input);

        //表单验证

        //添加到数据库
        $pername = $input['per_name'];
        $perurl = $input['per_url'];

        $res = Permission::create(['per_name'=>$pername,'per_url'=>$perurl]);

        if ($res){
            return redirect('admin/permission');
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
        $permission = Permission::find($id);

        return view('admin.permission.edit',compact('permission'));
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
        //1 根据id来获取来获取修改记录
        $permission = Permission::find($id);
        //2 获取要修改成的用户名
        $pername = $request->input('per_name');
        $perurl = $request->input('per_url');

        $permission->per_name = $pername;
        $permission->per_url = $perurl;

        $res = $permission->save();

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
        $permission = Permission::find($id);
        $res = $permission->delete();

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

    //批量删除
    public function delAll(Request $request)
    {
        $input = $request->input('ids');
        $res = Permission::destroy($input);

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
