<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classify extends Model
{
    protected $table = 'internet_classify';
    protected $guarded = ['id'];
    protected $fillable = ['category_title','sort','created_at','updated_at'];

}
