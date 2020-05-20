<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
class ArtisanController extends BaseController
{
   
    /**
     *生成
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
    	try{
            Artisan::call("cache:clear");
            if($request->controller ){
            	
            	Artisan::call("make:model ".$request->model);
            	//Artisan::call("make:controller ".$request->controller." --resource");
            }else{
            	throw new \Exception('参数异常');
            } 
            return Redirect::back()->with(['success'=>'添加成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors($exception->getMessage());
        }
    } 
}
