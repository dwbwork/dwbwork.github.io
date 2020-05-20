<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Configuration;
use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;

class ApiController extends Controller
{

    //文件上传
    public function upload(Request $request)
    {
        //支持的上传图片类型
        //返回信息json
        if(@$request->file_type ==2){//视频文件
            //上传文件最大大小,单位M
            $maxSize = 300;
            $allowed_extensions = ["mp3","rmvb","mp4"];
        }else{
            $allowed_extensions = ["png", "jpg", "gif"];
            //上传文件最大大小,单位M
            $maxSize = 50;
        }
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
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getSize() > $maxSize*1024*1024){
                $data['msg'] = "文件大小限制".$maxSize."M";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();

        $config = Configuration::where('group_id',5)->get()->toArray();

        $first = 4;
        if($first ==1){/*阿里云Oss普通上传*/
            /** 获取上传文件相关信息 */
            $originalName = $file->getClientOriginalName();     // 文件原名

            $realPath = $file->getRealPath();    //临时文件的绝对路径
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
            $res['code'] = 2;
            if($res){
                $data = [
                    'code'  => 0,
                    'msg'   => '上传成功',
                    'data'  => $res['src'],
                    'url'   => $res['src']
                ];
            }else{
                $data['data'] = $file->getErrorMessage();
            }
        }else if($first ==4){/*阿里云Oss分片上传*/
            /** 获取上传文件相关信息 */
            $originalName = $file->getClientOriginalName();     // 文件原名

            $realPath = $file->getRealPath();    //临时文件的绝对路径
            $accessKeyId = $config[0]['val'];
            $accessKeySecret = $config[1]['val'];
            // Endpoint以杭州为例，其它Reg'ion请按实际情况填写。
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            //$endpoint = "oss-cn-hangzhou-internal.aliyuncs.com";
            // 存储空间名称
            $bucket = $config[2]['val'];

            /**
             *  步骤1：初始化一个分片上传事件，获取uploadId。
             */

            try{
                $disk = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                //返回uploadId，它是分片上传事件的唯一标识，您可以根据这个ID来发起相关的操作，如取消分片上传、查询分片上传等。
                $uploadId = $disk->initiateMultipartUpload($bucket, $newFile);
                
                //$res = $disk->uploadFile($bucket,$newFile,$realPath);
              /*  $res['src'] = $res['info']['url'];
                $res['code'] = 2;

                    $data = [
                        'code'  => 0,
                        'msg'   => '上传成功',
                        'data'  => $res['src'],
                        'url'   => $res['src']
                    ];*/

            }catch(OssException $e){

                    //$data['data'] = $file->getErrorMessage();
                    $data['data'] = $e->getErrorMessage();

            }

            /*
             * 步骤2：上传分片。
             */
            $partSize = 10 * 1024 * 1024;
            $uploadFileSize = filesize($realPath);
            $pieces = $disk->generateMultiuploadParts($uploadFileSize, $partSize);
            $responseUploadPart = array();
            $uploadPosition = 0;
            $isCheckMd5 = true;
            foreach ($pieces as $i => $piece) {
                $fromPos = $uploadPosition + (integer)$piece[$disk::OSS_SEEK_TO];
                $toPos = (integer)$piece[$disk::OSS_LENGTH] + $fromPos - 1;
                $upOptions = array(
                    $disk::OSS_FILE_UPLOAD => $realPath,
                    $disk::OSS_PART_NUM => ($i + 1),
                    $disk::OSS_SEEK_TO => $fromPos,
                    $disk::OSS_LENGTH => $toPos - $fromPos + 1,
                    $disk::OSS_CHECK_MD5 => $isCheckMd5,
                );
                // MD5校验。
                if ($isCheckMd5) {
                    $contentMd5 = OssUtil::getMd5SumForFile($realPath, $fromPos, $toPos);
                    $upOptions[$disk::OSS_CONTENT_MD5] = $contentMd5;
                }
                try {
                    // 上传分片。
                    $responseUploadPart[] = $disk->uploadPart($bucket, $newFile, $uploadId, $upOptions);
                } catch(OssException $e) {
                    $data['data'] = $e->getErrorMessage();

                }

            }
            // $uploadParts是由每个分片的ETag和分片号（PartNumber）组成的数组。
            $uploadParts = array();
            foreach ($responseUploadPart as $i => $eTag) {
                $uploadParts[] = array(
                    'PartNumber' => ($i + 1),
                    'ETag' => $eTag,
                );
            }
            /**
             * 步骤3：完成上传。
             */
            try {
                // 在执行该操作时，需要提供所有有效的$uploadParts。OSS收到提交的$uploadParts后，会逐一验证每个分片的有效性。当所有的数据分片验证通过后，OSS将把这些分片组合成一个完整的文件。
                $res = $disk->completeMultipartUpload($bucket, $newFile, $uploadId, $uploadParts);
                $res['src'] = substr($res['info']['url'],0,strrpos($res['info']['url'],'?')) ;

                if($res){
                    $data = [
                        'code'  => 0,
                        'msg'   => '上传成功',
                        'data'  => $res['src'],
                        'url'   => $res['src']
                    ];
                }
            }  catch(OssException $e) {
                $data['data'] = $e->getErrorMessage();
               /* printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
                printf($e->getMessage() . "\n");
                return;*/
            }

        } else if($first ==2){/*七牛云上传*/
           /* $disk = QiniuStorage::disk('qiniu');
            $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
            if($res){
                $data = [
                    'code'  => 0,
                    'msg'   => '上传成功',
                    'data'  => $disk->downloadUrl($newFile),
                    'url'   => $disk->downloadUrl($newFile)
                ];
            }else{
                $data['data'] = $file->getErrorMessage();
            }*/
        }else{/*本地上传*/

            $disk = Storage::disk('uploads');
            $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
            if($res){
                $data = [
                    'code'  => 0,
                    'msg'   => '上传成功',
                    'data'  => $newFile,
                    'url'   => '/uploads/local/'.$newFile,
                ];
            }else{
                $data['data'] = $file->getErrorMessage();
            }
        }

        return response()->json($data);
    }

     
    //本地文件分片上传
    public function chunk_upload(Request $request){
        /** 获取上传文件相关信息 */
            $originalName = $file->getClientOriginalName();     // 文件原名

            $realPath = $file->getRealPath();    //临时文件的绝对路径
            $accessKeyId = $config[0]['val'];
            $accessKeySecret = $config[1]['val'];
            // Endpoint以杭州为例，其它Reg'ion请按实际情况填写。
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            //$endpoint = "oss-cn-hangzhou-internal.aliyuncs.com";
            // 存储空间名称
            $bucket = $config[2]['val'];
            /**
             *  步骤1：初始化一个分片上传事件，获取uploadId。
             */
            try{
                $disk = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                //返回uploadId，它是分片上传事件的唯一标识，您可以根据这个ID来发起相关的操作，如取消分片上传、查询分片上传等。
                $uploadId = $disk->initiateMultipartUpload($bucket, $newFile);
            
            }catch(OssException $e){
                    //$data['data'] = $file->getErrorMessage();
                    $data['data'] = $e->getErrorMessage();
            }

            /*
             * 步骤2：上传分片。
             */
            $partSize = 10 * 1024 * 1024;
            $uploadFileSize = filesize($realPath);
            $pieces = $disk->generateMultiuploadParts($uploadFileSize, $partSize);
            $responseUploadPart = array();
            $uploadPosition = 0;
            $isCheckMd5 = true;
            foreach ($pieces as $i => $piece) {
                $fromPos = $uploadPosition + (integer)$piece[$disk::OSS_SEEK_TO];
                $toPos = (integer)$piece[$disk::OSS_LENGTH] + $fromPos - 1;
                $upOptions = array(
                    $disk::OSS_FILE_UPLOAD => $realPath,
                    $disk::OSS_PART_NUM => ($i + 1),
                    $disk::OSS_SEEK_TO => $fromPos,
                    $disk::OSS_LENGTH => $toPos - $fromPos + 1,
                    $disk::OSS_CHECK_MD5 => $isCheckMd5,
                );
                // MD5校验。
                if ($isCheckMd5) {
                    $contentMd5 = OssUtil::getMd5SumForFile($realPath, $fromPos, $toPos);
                    $upOptions[$disk::OSS_CONTENT_MD5] = $contentMd5;
                }
                try {
                    // 上传分片。
                    $responseUploadPart[] = $disk->uploadPart($bucket, $newFile, $uploadId, $upOptions);
                } catch(OssException $e) {
                    $data['data'] = $e->getErrorMessage();

                }

            }
            // $uploadParts是由每个分片的ETag和分片号（PartNumber）组成的数组。
            $uploadParts = array();
            foreach ($responseUploadPart as $i => $eTag) {
                $uploadParts[] = array(
                    'PartNumber' => ($i + 1),
                    'ETag' => $eTag,
                );
            }
            /**
             * 步骤3：完成上传。
             */
            try {
                // 在执行该操作时，需要提供所有有效的$uploadParts。OSS收到提交的$uploadParts后，会逐一验证每个分片的有效性。当所有的数据分片验证通过后，OSS将把这些分片组合成一个完整的文件。
                $res = $disk->completeMultipartUpload($bucket, $newFile, $uploadId, $uploadParts);
                $res['src'] = substr($res['info']['url'],0,strrpos($res['info']['url'],'?')) ;

                if($res){
                    $data = [
                        'code'  => 0,
                        'msg'   => '上传成功',
                        'data'  => $res['src'],
                        'url'   => $res['src']
                    ];
                }
            }  catch(OssException $e) {
                $data['data'] = $e->getErrorMessage();
               /* printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
                printf($e->getMessage() . "\n");
                return;*/
            }
    }
    
    //本地文件切片上传
    public function one_upload($path,$name){
          /** 获取上传文件相关信息 */
           $config = Configuration::where('group_id',5)->get()->toArray();
            $accessKeyId = $config[0]['val'];
            $accessKeySecret = $config[1]['val'];
            // Endpoint以杭州为例，其它Reg'ion请按实际情况填写。
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            //$endpoint = "oss-cn-hangzhou-internal.aliyuncs.com";
            // 存储空间名称
            $bucket = $config[2]['val'];
            $object = date('Y-m-d')."_".time().$name;
            
            $file = $path;
            $options = array(
                OssClient::OSS_CHECK_MD5 => true,
                OssClient::OSS_PART_SIZE => 1,
            );
            try{
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                $result = $ossClient->multiuploadFile($bucket, $object, $path, $options);
               
          
             return substr($result['info']['url'],0,strrpos($result['info']['url'],'?'));
            ;

            } catch(OssException $e) {
                
               return false;
                /*printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return;*/
            }
    }
   
   /**
     * @Desc: 大视频切片上传
     *
     * @param Request $request
     * @return mixed
     */
    public function VideoUpload(Request $request)
    {
        $file = $request->file('file');
        $blob_num = $request->get('blob_num');
        $total_blob_num = $request->get('total_blob_num');
        $file_name = $request->get('file_name');

        $realPath = $file->getRealPath(); //临时文件的绝对路径

        // 存储地址
        $path = '/local/slice/'.date('Ymd')  ;
        $filename = $path .'/'. $file_name . '_' . $blob_num;

        //上传
        $upload = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

        $url = '/uploads/local'.$path.'/'.$file_name;
        //判断是否是最后一块，如果是则进行文件合成并且删除文件块
        if($blob_num == $total_blob_num){
            for($i=1; $i<= $total_blob_num; $i++){
                $blob = Storage::disk('uploads')->get($path.'/'. $file_name.'_'.$i);
        //不能用这个方法，函数会往已经存在的文件里添加0X0A，也就是\n换行符
                file_put_contents(public_path('uploads').'/local/'.$path.'/'.$file_name,$blob,FILE_APPEND);


            }
           //合并完删除文件块
            for($i=1; $i<= $total_blob_num; $i++){
                Storage::disk('uploads')->delete($path.'/'. $file_name.'_'.$i);
            }
            $msg ='全部上传成功';
            //上传至阿里云OSS
            /* $url = $this->one_upload(public_path('uploads').'/local/'.$path.'/'.$file_name,$file_name);*/
             }else{
                $msg ='分片上传成功';
             }
        $url ='/uploads/local'.$path.'/'.$file_name;
        $data=(object)null;
        if ($upload){

            $data->readyState =4;
            $data->status =200;
            $data->msg = $msg;
            $data->src = $url;
            return json_encode($data);

        }else{
            $data->msg ='失败';
            $data->status =202;
            return $data;
        }

    }

}
