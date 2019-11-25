<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //1 关联的数据表
    public $table = 'permission';

    //2 主键
    public $primaryKey = 'id';

    // 允许操作的字段
    //public $fillable = ['user_name','user_pass','email','phone'];
    public $guarded = [];//不允许操作的字段为空，就代表所有的字段都能操作

    //4 是否维护crated_at 和 updated_at 字段
    public $timestamps = false;
}
