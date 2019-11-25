<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //1 关联的数据表
    public $table = 'role';

    //2 主键
    public $primaryKey = 'id';

    // 允许操作的字段
    //public $fillable = ['user_name','user_pass','email','phone'];
    public $guarded = [];//不允许操作的字段为空，就代表所有的字段都能操作

    //4 是否维护crated_at 和 updated_at 字段
    public $timestamps = false;

//    添加动态属性，关联权限模型
    public function permission()
    {
        return $this->belongsToMany('App\Model\Permission','role_permission','role_id','permission_id');
    }
}
