
<script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
<script>
    layui.use(['form', 'formX'], function () {
        var $ = layui.jquery;
        var form = layui.form;
        var formX = layui.formX;

        // 监听表单提交
        form.on('submit(submitDemo)', function (data) {
            layer.load(2);
                        $.post(url, data.field, function (res) {
                            layer.closeAll('loading');
                            if (res.code == 200) {
                                layer.close(dIndex);
                                layer.msg(res.msg, {icon: 1});
                                insTb.reload({}, 'data');
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }, 'json');
                        return false;
        });

        $('#closeFormBtn').click(function () {
            
            layer.close(layer.index);
            //$(this).parent().parent().parent().remove();
        });

    });
</script>


