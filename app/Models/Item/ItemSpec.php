<?php
// +----------------------------------------------------------------------
// | 规格类模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class ItemSpec extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item_spec';
    protected $guarded = ['spec_id'];
    protected $fillable = [
    'spec_name','updated_time','full_name','created_time'
        
    ];

    //规格值列表
    public function spec_value()
    {
        return $this->hasMany('App\Models\Item\ItemSpecValue','spec_id','spec_id');
    }
}
