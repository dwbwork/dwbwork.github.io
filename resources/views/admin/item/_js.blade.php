
<script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>

<script>
    layui.use(['upload','layer','element','form','layedit'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var upload = layui.upload;
        var form = layui.form;
        var element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
        
        var layedit = layui.layedit;
           layedit.set({
               uploadImage:{
                   url:'{{ route("api.upload") }}'//接口url
                   ,type:''//默认post
               }});

        var editIndex = layedit.build('qaContent'); // 建立编辑器

        var actives = {
            content:function(){
                alert(layedit.getContent(editIndex));//获取编辑器内容
            },
            text:function(){
                alert(layedit.getText(editIndex));//获取编辑器纯文本内容
            }
            ,selection:function(){
                alert(layedit.getSelection(editIndex));
            }
        };
        $('.site-demo-layedit').on('click',function(){
            var type = $(this).data('type');
            actives[type] ? actives[type].call(this) :'';
        });
       
        /*tab切换事件*/
        //触发事件
          var active = {
            tabChange: function(){
              //切换到指定Tab项
              element.tabChange('demo', '22'); //切换到：用户管理
            }
          };
          
          

          
        
        // 表单提交事件
        form.on('submit(formSubmitAQ)', function (data) {
            
        
        });

        // 试题类型切换事件
        form.on('radio(raQT)', function (data) {
            changeQT(data.value);
        });
          
        function changeQT(value, sel) {
            if (value == 1) {
                /*$('#qaRIGroup').html('');
                $('#qaRIGroup').html($('#qaRI1').html());*/
                $('#qaRI1').show(); 
                $('#qaRI2').hide(); 
            } else if (value == 2) {
                /*$('#qaRIGroup').html('');
                $('#qaRIGroup').html($('#qaRI2').html());*/
               $('#qaRI1').hide(); 
               $('#qaRI2').show(); 
                
            } 
        }
/*
        setTimeout(function () {
            changeQT(1);
        }, 50);
*/
           //普通图片上传
        $(".uploadPic").each(function (index,elem) {
            upload.render({
                elem: $(elem)
                ,url: '{{ route("api.upload") }}'
                ,multiple: false
                ,data:{"_token":"{{ csrf_token() }}"}
                ,done: function(res){
                    //如果上传失败
                    if(res.code == 0){
                        layer.msg(res.msg,{icon:1,time:1000},function () {
                            $(elem).parent('.layui-upload').find('.layui-upload-box').html('<li><img style="width: 100px;height:60px" src="'+res.url+'" /></li>');
                            $(elem).parent('.layui-upload').find('.layui-upload-input').val(res.url);
                        })
                    }else {
                        layer.msg(res.msg,{icon:2,time:1000})
                    }
                }
            });
        })
       
        //多图片上传
        upload.render({
            elem: '#test2',
            url: '{{ route("api.upload") }}'
            //上传接口
            ,
            data:{"_token":"{{ csrf_token() }}"},
            multiple: false,

            before:function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
//                    $('#demo2').append()
                });
            },
            done: function (res) {
                //上传完毕
                if (res.code == 0) {
                    //console.log(111)
                    $('#demo2').append('<li class="item_img"><div class="operate"></i><i  class="close layui-icon"></i></div><img src="' + res.url + '" class="layui-upload-img" width="60"><input type="hidden" name="image[]" value="' + res.url + '"></li>');
                } else {
                    layer.msg(res.msg,{icon:2,time:1000})
                }
            }
        });
//点击多图上传的X,删除当前的图片
        $("body").on("click",".close",function(){
            $(this).closest("li").remove();
        });

    });
</script>

