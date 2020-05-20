<?php
// +----------------------------------------------------------------------
// | 后台上传文件控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Models\Files;
use App\Models\FileGroup;
use Illuminate\Http\Request;
use App\ExtendClass\UploadFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Configuration;
use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;
use App\Http\Controllers\Admin\BaseController;

class FilesController extends BaseController
{
     /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Files();
    }

    public function getModel()
    {
        return new Files();
    }
    public function handle($type, Request $request)
    {
        if ($type == 'upload') {
            return $this->upload($request);
        }
        if ($type == 'list') {
            return $this->getList($request);
        }
        if ($type == 'files') {
            //图片空间
            $this->setModelControllerView('fileupload');
            return $this->getList($request);
        }
        if ($type == 'api') {
            return $this->getApi($request);
        }
        if ($type == 'addGroup') {
            return $this->addGroup($request);
        }
        if ($type == 'getGroup') {
            return $this->getGroup($request);
        }
    } 
     /**
     * 获取分组
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
   public function getGroup()
    {

        $list = FileGroup::get()->toArray();
        
        return $this->returnOkApi('请求成功', $list);
    }
    
    /**
     * 首页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
         
        return [];
    }
    /**
     * 获取搜索条件
     *
     * @return \Illuminate\Http\Response
     */
    public function get_where($where,$model){
       
        $array=[];
        $where = array_filter($where);
        
       if (@$where['group_id']){
            $model = $model->where('group_id',$where['group_id']);
        }
       
        return $model;
    }

    /**
     * 添加分组
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function addGroup(Request $request)
    {
        $name = $request->input('name');
        $data = [
            'name' => $name,
             
            'model_type' => 'admin'
        ];
        
        $r = FileGroup::create($data);
        if ($r) {
            return $this->returnOkApi('添加成功', $r->id);
        }
        return $this->returnErrorApi('添加失败');
    }
    /**
     * 上传图片
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function upload($request)
    {

        $files = $request->input('files', 'file');
        $data['screen'] = $request->input('screen', '');
        $data['type'] = $request->input('uptype', 'image');
        $data['group_id'] = $request->input('group_id', '0');
        $method = $request->input('method', 'upload');
        

        //返回信息json
       
        $allowed_extensions = ["png", "jpg", "gif"];
        //上传文件最大大小,单位M
        $maxSize = 50;
        try{

        
        $file = $request->file('file');
        
        if(!$file){
            $data = [
                'code'  => 1,
                'msg'   => '请选择有效文件',

            ];
            return response()->json($data);

        }
        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();

            if (!in_array(strtolower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的文件";
                $data['code'] = 202;
               throw new Exception($data['msg']);
            }
            $data['ext']=$ext;
            //检测图片大小
            if ($file->getSize() > $maxSize*1024*1024){
                $data['msg'] = "文件大小限制".$maxSize."M";
                throw new Exception($data['msg']);
            }
             $data['size']=$file->getSize();
        }else{
            throw new Exception($file->getErrorMessage());  
           
        }
        $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();

        $config = Configuration::where('group_id',5)->get()->toArray();

           /*阿里云Oss普通上传*/
            /** 获取上传文件相关信息 */
            $originalName = $file->getClientOriginalName();     // 文件原名
            $data['tmp'] = $originalName;
            $realPath = $file->getRealPath();    //临时文件的绝对路径
            $data['filename'] = $realPath;
            $data['oss_type'] = '阿里云OSS';
            $accessKeyId = $config[0]['val'];
            $accessKeySecret = $config[1]['val'];
            // Endpoint以杭州为例，其它Reg'ion请按实际情况填写。
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            // 存储空间名称
            $bucket = $config[2]['val'];
            //判断使用云存储
            $disk = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $res = $disk->uploadFile($bucket,$newFile,$realPath);
            $res['src'] = $res['info']['url'];
            $data['url'] = $res['src'];
            Files::create($data);
            $data = [
                    'code'  => 200,
                    'msg'   => '上传成功',
                    'data'  => $res['src'],
                    'url'   => $res['src']
                ];
           
            } catch(OssException $e) {
                $data['data'] = $e->getErrorMessage();
             
            }

        
        return response()->json($data);
    }
     /**
     * 获取列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */ 
    protected function getList($request)
    {
        return $this->display([]);
    }

    protected function getApi($request)
    {
        $type = $request->input('uptype', '');
        $screen = $request->input('screen', '');
        $is_oss = $request->input('is_oss', false);
        $offset = $request->input('offset', 1);
        $pagesize = $request->input('limit', 1);
        $offset = ($offset - 1) * $pagesize;
        $key = $request->input('search', '');

        $model = new File();
        if ($type) {
            if ($type == 'file') {
                $model = $model->whereIn('type', ['file', 'zip', 'excel','vedio']);
            } else {
                $model = $model->where('type', $type);
            }

        }

        if ($screen) {
            $model = $model->where('screen', $screen);
        }
        $total = $model->where('user_type', 'admin')->count();

        $data = $model->where('user_type', 'admin')->skip($offset)->take($pagesize)->orderBy('id', 'desc')->get()->toArray();
        $str = '';
        $uindex = 0;
        foreach ($data as $key => $v) {
            $pic_url = $v['path'];
            $is_oss = $is_oss ? $is_oss : config('website.is_oss');
            $v['oss'] = '';
            if ($is_oss) {
                if (Storage::exists($pic_url)) {
                    $pic_url = Storage::url($v['path']);
                    $v['oss'] = Storage::url($v['path']);
                }

            }
            $img_pic = ($v['path']);
            if (in_array($v['ext'], ['.xlsx', '.xls'])) {
                $img_pic = 'excel.jpg';
                $img_pic = ___('/admin/images/' . $img_pic);
            }
            if (in_array($v['ext'], ['.doc', '.docx'])) {
                $img_pic = 'word.jpg';
                $img_pic = ___('/admin/images/' . $img_pic);
            }
            if (in_array($v['ext'], ['zip', ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2"])) {
                $img_pic = 'zip.jpg';
                $img_pic = ___('/admin/images/' . $img_pic);
            }
            if (in_array($v['ext'], ['zip', ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2"])) {
                $img_pic = 'zip.jpg';
                $img_pic = ___('/admin/images/' . $img_pic);
            }
            if (in_array($v['ext'], [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".wmv",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid", ".cab", ".iso",
                ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml", ".psd", ".ai", ".cdr"
            ])) {
                $img_pic = 'file.jpg';
                $img_pic = ___('/admin/images/' . $img_pic);
            }

            $img_html = ' <img data-path="' . $pic_url . '" data-view_src="' . $img_pic . '"  data-tmpname="' . $v['tmp'] . '" data-oss="' . $v['oss_type'] . '" data-ext="' . $v['ext'] . '"  data-type="' . $v['type'] . '" src="' . $img_pic . '" class="upload-item-pic" title="' . $v['tmp'] . '" alt="" > ';
            if ($v['type'] == 'vedio') {
                $img_html = ' <video  class="upload_img" src="' . $v['path'] . '" data-type="' . $v['type'] . '" data-src="' . $v['path'] . '" controls="controls"  style="width: 100%;"></video>';
            }

            $str .= ' 
                    <div class="item item2 layui-col-xs4 layui-col-sm3 layui-col-md2 tupload-item upload-item-more "> 
                   ' . $img_html . '
                    <div class="item-foot-tools"> <p>' . $v['tmp'] . '</p> 
                    </div> </div>';
            $uindex++;
        }

        $list = [
            'total' => $total,
            'contents' => $str,
            'pagesize' => $pagesize
        ];
        $debug = $request->input('debug', 0);
        if ($debug) {
            return $this->jsonDebug($list);
        }
        return response()->json($list);
    }
}
