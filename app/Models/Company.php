<?php
// +----------------------------------------------------------------------
// | 合作商模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $guarded = ['id'];
    protected $fillable = ['name','description','sort'];



}
