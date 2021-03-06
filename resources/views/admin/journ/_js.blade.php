<script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
<script>
    layui.use(['upload','layer','element','layedit','form'],function () {
        var $ = layui.jquery;
        var upload = layui.upload;
        var form = layui.form;
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
        form.on('select(position_id)', function(data){
            if(data.value == 1 || data.value == 4){
                $("#net").removeAttr("disabled");
                form.render('select');

            }else{

                $("#net").attr("disabled","true");
                form.render('select');
            }
            if(data.value ==4){
                $('#process').show();
            }else{
                $('#process').hide();
            }
        });

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
                            $(elem).parent('.layui-upload').find('.layui-upload-box').html('<li><img style="width:100px;height:60px" src="'+res.url+'" /></li>');
                            $(elem).parent('.layui-upload').find('.layui-upload-input').val(res.url);
                        })
                    }else {
                        layer.msg(res.msg,{icon:2})
                    }
                }
            });
        })

    })


</script>