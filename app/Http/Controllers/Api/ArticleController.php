<?php
/*
 * 文章控制器
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WxUsers;
use App\Models\Article;
use App\Models\News;
use App\Models\AccountCate;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Redis;
use App\Models\Store;
use App\Models\TopicCollect;
use Illuminate\Support\Facades\Crypt;
class ArticleController extends Controller
{
    /*新闻动态 || 校园风采 || 热门新闻*/

    public function index(Request $request){
        switch($request->input('cate')){
            case 1 :$model = Article::query();break;
            case 2 :$model = News::query();;break;
            case 3 :$model = Article::query(); $model = $model->where('is_top',1);break;
        }
        if($request->input('name')){
            $model = $model->where('title','like','%'.$request->get('name').'%');
        }

        $res = $model->where('status',1)->orderBy('is_top','desc')->orderBy('sort','asc')->paginate($request->input('limit',5))->toArray();

        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }


    /**
     * 用户动态
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function topic(Request $request)
    {
        $model = Topic::query();
        if($request->input('name')){
            $model = $model->where('title','like','%'.$request->get('name').'%');
        }
        $res = $model->where('status',1)->orderBy('is_top','desc')->orderBy('sort','asc')->with(['wx_user'])
            ->paginate($request->input('limit',10))->toArray();



        foreach ($res['data'] as $key => $value) {
            $res['data'][$key]['comment'] = DB::table('comment')->where('topic_id',$value['id'])->get();
            $res['data'][$key]['images'] = array_filter(explode(',', $value['image']));
        };
        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }
    /**
     * Show the form for creating a new resource.
     * 发布帖子
     * @return \Illuminate\Http\Response
     */
    public function create_topic(Request $request)
    {
        $data = $request->only(['title','image','info','video','video_map']);
        //验证用户信息

        $userInfo= WxUsers::findOrFail($request->user_id);

        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };

        $data['user_id'] = $userInfo->id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $article = DB::table('topic')->insertGetId($data);


        return Response()->json(['code'=>200,'msg'=>'发布成功']);


    }

    /**
     * Show the form for editing the specified resource.
     * 资讯详情
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function article_detail(Request $request)
    {


        if($request->type == 1){//新闻
            $info = Article::findOrFail($request->id);
            /*增加一次浏览量*/
            Article::where('id',$request->only('id')['id'])->increment('click');
        }else{//商家新闻
            $info = News::findOrFail($request->only('id')['id']);
            /*增加一次浏览量*/
            News::where('id',$request->only('id')['id'])->increment('click');
        }

        if(!$info){
            return Response()->json(['code'=>202,'msg'=>'帖子不存在']);
        };

        /*浏览记录*/
        /* $history=['user_id'=>$userInfo[0]['id'],'topic_id'=>$request->only('id')['id']];
         if(history::where($history)->first()){
             history::where($history)->update(['updated_at'=>date('Y-m-d H:i:s')]);
         }else{
             $res = history::create($history);
         }*/


        $info->images= array_filter(explode(',', $info->image));

       // $comment= Comment::orderBy('created_at', 'desc')->where('topic_id',$request->only('id'))->get();

        return Response()->json(['code'=>200,'msg'=>'返回成功','data'=>['info'=>$info,/*'comment'=>$comment*/]]);

    }


    /**
     * Show the form for editing the specified resource.
     * 帖子详情
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function topic_detail(Request $request)
    {
        //验证用户信息

        $info = Topic::with(['wx_user','comment'=>function($query){
            $query->where('pid',0)->with(['childs'=>function($query){
                $query->with('wx_users');
            },'wx_users'])->get();
            }])->findOrFail($request->id)->toArray();


        if(!$info){
            return Response()->json(['code'=>202,'msg'=>'帖子不存在']);
        };

        /*增加一次浏览量*/
        Topic::where('id',$request->only('id')['id'])->increment('views');

        $info['ad']=[];
        if($info['image'] !=''){
            $info['images'] = explode(',',$info['image']);
            foreach($info['images'] as $k=>$v){
                $info['ad'][$k]['type'] = 1;
                $info['ad'][$k]['src'] = $v;
            }
        }
        //实例化redis
        $redis = Redis::connection();
        if($request->key && $request->key!="undefined" && $redis->exists(Crypt::decryptString(@$request->key)) ){

            $phone = Crypt::decryptString(@$request->key);
            $user = WxUsers::where('phone',$phone)->first();

            $request->user_id =$user->id;



        }else{
            $request->user_id =0;
        }
        /*判断是否点赞*/
        if(TopicCollect::where(['user_id'=>$request->user_id,'topic_id'=>$request->id])->exists()){
            $info['is_collect'] = 1;
        }else{
            $info['is_collect'] = 0;

        }

        /*判断是否为作者*/
        if($info['user_id'] == $request->user_id){
            $info['is_author'] = 1;
        }else{
            $info['is_author'] = 0;
        }
        $info['video'] !='' && array_push($info['ad'],['type'=>2,'src'=>$info['video']]);

        return Response()->json(['code'=>200,'msg'=>'返回成功','data'=>['info'=>$info]]);

    }

    /*发布评论*/
    public function push_comment(Request $request){
        $data=$request->only(['topic_id','content']);
        $info = Topic::findOrFail($request->topic_id);

        if(!$info){
            return Response()->json(['code'=>202,'msg'=>'帖子不存在']);
        };
        $data['from_uid'] = $request->user_id;

        $article = Comment::create($data);
        if($article){
            Topic::where('id',$request->topic_id)->increment('comments');
            return response()->json(['code'=>200,'msg'=>'评论成功']);
        }
        return response()->json(['code'=>202,'msg'=>'评论成功']);
    }

    /*回复评论*/

    public function answer_comment(Request $request){
        $data=$request->only(['content','pid']);

        $data['from_uid'] = $request->user_id;
        //查询单挑评论记录
        $comment = Comment::findOrFail($request->pid);
        $data['from_uid'] = $request->user_id;
        $data['review_id'] = $comment['from_uid'];
        $data['topic_id'] = $comment['topic_id'];
        $article = Comment::create($data);
        if($article){
            Topic::where('id',$request->topic_id)->increment('comments');
            return response()->json(['code'=>200,'msg'=>'评论成功']);
        }
        return response()->json(['code'=>202,'msg'=>'评论失败']);
    }


    /*我的发布*/
    public function my_topic(Request $request){
        $model = Topic::query();

        $model = $model->where('user_id',$request->user_id);
        switch($request->status){
            case 0:$model = $model->where('status',1);break;//已发布
            case 1:$model = $model->where('status',0);break;//待审核
            case 2:$model = $model->where('status',2);break;//驳回
        }
        $res = $model->orderBy('created_at','desc')->paginate($request->input('limit',10))->toArray();

        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

    /*帖子点赞*/
    public function topic_collect(Request $request){
        try{
            if(TopicCollect::where(['user_id'=>$request->user_id,'topic_id'=>$request->topic_id])->exists()){
                TopicCollect::where(['user_id'=>$request->user_id,'topic_id'=>$request->topic_id])->delete();
                Topic::where('id',$request->topic_id)->decrement('collect',1);
            }else{
                TopicCollect::create(['user_id'=>$request->user_id,'topic_id'=>$request->topic_id]);
                Topic::where('id',$request->topic_id)->increment('collect',1);
            }
            return response()->json(['code'=>200,'msg'=>'成功']);
        }catch(\Exception $e){
            return response()->json(['code'=>202,'msg'=>'失败']);
        }

    }

    /*帖子转发*/
    public function topic_trans(Request $request){
        try{

            Topic::where('id',$request->topic_id)->increment('trans');

            return response()->json(['code'=>200,'msg'=>'成功']);
        }catch(\Exception $e){
            return response()->json(['code'=>202,'msg'=>$e->getMessage()]);
        }

    }

}
