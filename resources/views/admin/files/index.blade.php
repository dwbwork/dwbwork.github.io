@extends('admin.base')
 <style>
     /** 项目列表样式 */
        .project-list-item {
            background-color: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            cursor: pointer;
            transition: all .2s;
        }

        .project-list-item:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        .project-list-item .project-list-item-cover {
            width: 100%;
            height: 220px;
            display: block;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .project-list-item-body {
            padding: 20px;
        }

        .project-list-item .project-list-item-body > h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
        }

        .project-list-item .project-list-item-text {
            height: 44px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .project-list-item .project-list-item-desc {
            position: relative;
        }

        .project-list-item .project-list-item-desc .time {
            color: #999;
            font-size: 12px;
        }

        .project-list-item .project-list-item-desc .ew-head-list {
            position: absolute;
            right: 0;
            top: 0;
        }

        .ew-head-list .ew-head-list-item {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid #fff;
            margin-left: -5px;
        }

        .ew-head-list .ew-head-list-item:first-child {
            margin-left: 0;
        }

        /** // 项目列表样式结束 */

 </style>

@section('content')
   

<!-- 正文开始 -->
<div class="layui-fluid">
    


    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li class="layui-this">文件库</li>
                   
                  
                </ul>
                <div class="upload-category" style="margin-top: 20px">
                    <form class="layui-form" action="">
                        <div class="layui-form-item">
                            <label class="layui-form-label text-left">存入所属组</label>
                            <div class="layui-input-inline">
                                <select lay-filter="listGroup" name="group_id" id="listGroup">


                                </select>
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                <a href="javascript:void(0)"class="text-primary js-add">添加</a>

                            </div>
                            <div class="layui-input-inline">
                            <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>上传图片</button>
                            </div>
                            
                        </div>
                    </form>
                    <div class="upload-categor-form layui-hide">
                        <form class="layui-form" action="">
                            <label class="layui-form-label text-left">名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" required lay-verify="required"
                                       placeholder="请输入名称" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-input-inline">
                                <button class="layui-btn" lay-submit lay-filter="addGroup">添加</button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show" style="padding-top: 25px;">
                        <div class="layui-row layui-col-space30" id="demoGrid2"></div>

                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>



<!-- 项目模板 -->
<script type="text/html" id="demoGridItem2">
    <div class="layui-col-md3">
       
        <div class="project-list-item" >
            <img class="project-list-item-cover" src=" @{{(d.url)}}"/>
            <div class="project-list-item-body">
                <h2>@{{(d.oss_type)}}</h2>
                <div class="project-list-item-text layui-text">@{{(d.url)}}</div>
                
            </div>
        </div>


        
    </div>

</script>



@section('script')
<script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('config.files')
       
<script>
     var group_id;
    layui.use(['layer','upload', 'dataGrid','form','admin','element', 'dropdown'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var dataGrid = layui.dataGrid;
        var admin = layui.admin;
        var element = layui.element;
        var form = layui.form;  
        var upload = layui.upload;
        //指定允许上传的文件类型
      
       

         

        function file_data(){
             // 文件库
        
        $.get("{{route('admin.files.data')}}", {'group_id':group_id},function (res) {
            dataGrid.render({
                elem: '#demoGrid2',  // 容器
                templet: '#demoGridItem2',  // 模板
                data: res.data,  // url
                
                page: {limit: 8, limits: [8, 16, 24, 32, 40]},  // 开启分页
                fit:true,
                onItemClick: function (obj) {  // item事件
                    
                    var index = obj.index + 1;
                    layer.msg('点击了第' + index + '个', {icon: 1});
                },
                done:function(data,curr,count){
                    console.log('--------------------');
                    console.log(data);
                    console.log(curr);
                    console.log(count);
                }
            });
        }, 'json');
        }
       
        file_data();
        //上传图片
         upload.render({
         
                    elem: '#test3'
                    ,url: "{{route('admin.upload',['type'=>'upload'])}}"
                    ,accept: 'file' //普通文件
                    ,data: {group_id:function () {
                        return $('#listGroup').val();
                    }} 
                    ,exts: 'png|jpeg|jpg' //只允许上传压缩文件
                    ,done: function(res){
                      //如果上传失败
                            if(res.code == 200){
                                layer.msg(res.msg,{icon:1,time:1000},function () {
                                     file_data();
                                     })
                            }else {
                                layer.msg(res.msg,{icon:2,time:1000})
                            }
                    }
                  });
          //添加分组
            form.on('submit(addGroup)', function (data) {

                $.post("{{ route('admin.upload',['type'=>'addGroup']) }}", data.field, function (res) {
                    layui.layer.msg(res.msg);
                    if (res.code == 200) {
                        getGroup(res.data);
                        //修改分组
                        uploadObj.config.data.group_id = res.data;
                        $("#upload-area").attr('group_id', res.data);
                        $(".upload-categor-form").toggleClass('layui-hide');
                    } else {

                    }
                })
                return false;

            });

            //切换分组
            form.on('select(listGroup)', function (data) {
                //修改分组
               group_id = $('#listGroup').val();
                file_data();
            });
            

            /**
             * 取得分组
             * @param id
             */
            function getGroup(id) {
                
                //请求分组
                $.post("{{ route('admin.upload',['type'=>'getGroup']) }}", {}, function (res) {

                    var html_str = '<option value="0">默认</option>';
                    var select_str = '';
                    if (res.data.length > 0) {

                        for (var i in res.data) {
                            if (res.data[i]['id'] == id) {
                                select_str = 'selected';
                            } else {
                                select_str = '';
                            }
                            html_str += '<option value="' + res.data[i]['id'] + '" ' + select_str + '>' + res.data[i]['name'] + '</option>';
                        }
                    }
                    $("#listGroup").empty().append(html_str);
                    form.render('select'); //刷新select选择框渲染
                })
            }

            getGroup();

            $(".js-add").click(function () {
                $(".upload-categor-form").toggleClass('layui-hide');
            })
        

    });
</script>


    @endcan
@endsection