<?php
// +----------------------------------------------------------------------
// | 规格值模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class ItemSpecValue extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item_spec_value';
    protected $guarded = ['spec_value_id'];
    protected $fillable = [
    'spec_id','spec_value','updated_at','created_at'
        
    ];
     
    //所属规格类
    public function category()
    {
        return $this->belongsTo('App\Models\Item\ItemSpec','spec_id','spec_id')->withDefault(['name'=>'无规格类']);
    }
    
}
