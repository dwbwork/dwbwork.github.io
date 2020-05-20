<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/static/admin/layuiadmin/style/login.css" media="all">
</head>
<style>
    html,
    body {
        width: 100%;
        height: 100%;
    }

    body,
    p {
        margin: 0;
        padding: 0;
    }

    .indexs {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        /* flex-grow: 1; */
        justify-content: space-between;
        padding-bottom: 5vh;
        box-sizing: border-box;
        background: #fff;
    }

    .back_con {
        width: 100%;
        height: 380px;
        background-image: url('/static/admin/back_con.png');
        background-size: 100% 100%;
    }

    .top_flag {
        display: flex;
        align-items: center;
        padding-top: 4vh;
        margin-left: 14%;
        color: #fff;
    }

    .flag_le {
        font-size: 30px;
        font-weight: bold;
    }

    .flag_ri {
        font-size: 16px;
        border-left: 1px solid #fff;
        margin-left: 8px;
        padding-left: 8px;
    }

    .contents {
        display: flex;
        flex-direction: column;
        margin-top: 4vh;
    }

    .cons {
        width: 528px;
        margin: 0 auto;
    }

    .con_head {
        width: 100%;
        display: flex;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        color: #fff;
        align-items: center;
    }

    .head_img {
        width: 60px;
        height: 50px;
        flex-shrink: 0;
        margin-right: 19px;
    }

    .con_bot {
        flex-shrink: 0;
        color: #3D4456;
        font-size: 16px;
        text-align: center;
        position: relative;
        top: 200px;
    }

    .con_bot span {
        color: #2693C1;
        padding-left: 10px;
    }
    .update_form{
    margin-top: 4vh;
    border: 1px solid rgba(38, 147, 193, 0.2);
    border-radius: 5px;
    height: 380px;
    }
    .layadmin-user-login-header{
      background: #3AADDE;
    color: #fff;
    font-size: 18px;
    letter-spacing: 2px;
    height: 63px;
    line-height: 63px;
    }
    .btn_login{
      width:156px;
      height:48px;
      line-height:48px;
      border-radius:6px;
      background:#2693C1;
      text-align:center;
      font-size:18px;
      color:#fff;
      margin: 0 auto;
      cursor:pointer;
    }
    .layadmin-user-login-box{
      background: #fff;
    }
    .layui-form-item{
      display: flex;
    align-items: center;
    width: 307px;
    margin: 0 auto;
    }
    .layadmin-user-login-body .layui-form-item{
      margin-bottom: 16px;
    }
    .layui-form-item .layui-form-checkbox{
      display:none;
    }
    .item_div{
      flex-shrink: 0;
    color: #141B17;
    width: 66px;
    font-size: 16px;
    }
    .item_inp{
      width:100%;
    }
    .layui-inputs{
      padding-left:12px!important;
    }
    .remember{
      color:rgba(39, 42, 39, .5);
      font-size:16px;
    }
    /* .layadmin-user-login-body .layui-form-item .layui-input{
      margin-left:12px;
    } */

    input[type=checkbox]::after{
     position: absolute;
     top: 4px;
     background-color: #fff;
     color: #000;
     width: 14px;
     height: 14px;
     display: inline-block;
     visibility: visible;
     padding-left: 0px;
     text-align: center;
     content: ' ';
     border-radius: 2px;
     border:1px solid #CBD4DA;
}

input[type=checkbox]:checked::after{
     content: "✓";
     font-size: 12px;
     font-weight: bold;
     color: #009E9E;
}

</style>
<body>
<div class="indexs">
    <div class="back_con">
        <div class="top_flag">
            <div class="flag_le">星数为来</div>
            <div class="flag_ri">星数据 为来而来</div>
        </div>
        <div class="contents">
            <div class="cons">
                <div class="con_head">
                    <img src="/static/admin/logo.png" class="head_img" />
                    <div>星数为来后台系统</div>
                </div>

                <div class="update_form">
                    <div class="layadmin-user-login-header">
                        您好！欢迎来到星数为来后台系统
                    </div>
                    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                        <form action="{{route('admin.user.login')}}" method="post" class="layui-form">
                            {{csrf_field()}}
                            <div class="layui-form-item">
                                <div class="item_div">账  号</div>
                                <div class="item_inp">
                                    <!-- <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                                           for="LAY-user-login-username"></label> -->
                                    <input type="text" name="username" maxlength="16" value="{{old('username')}}" lay-verify="required"
                                           placeholder="用户名" class="layui-input layui-inputs">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="item_div">密  码</div>
                                <div class="item_inp">
                                    <!-- <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                                           for="LAY-user-login-password"></label> -->
                                    <input type="password" name="password" maxlength="16" lay-verify="required" placeholder="密码"
                                           class="layui-input layui-inputs">
                                </div>
                            </div>
                            <div class="layui-form-item">
                        <div class="item_div">验证码</div>
                        <div class="item_inp">
                            <div class="layui-row">
                                <div class="layui-col-xs7">
                                    <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                                           for="LAY-user-login-vercode"></label>
                                    <input type="text" name="captcha" maxlength="4" id="LAY-user-login-vercode"
                                           lay-verify="required" placeholder="验证码" class="layui-input">
                                </div>
                                <div class="layui-col-xs5">
                                    <div style="margin-left: 10px;">
                                        <img src="{{captcha_src()}}" id="captcha_img" onclick="this.src=this.src+'?t='+Math.random()" class="layadmin-user-login-codeimg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="btn_login">
                      <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="*" style="background: #2693C1;">登 录</button>
                    </div>
                     </form>
                </div>
            </div>
        </div>
      </div>
        <div class="con_bot">Copyright ©2020 星数为来平台系统v1.0 技术支持:<span>星数为来科技开发团队</span></div>
    </div>
    <script src="/static/admin/layuiadmin/layui/layui.js"></script>
<script>
    layui.config({
        base: '/static/admin/layuiadmin/' //静态资源所在路径
    }).use(['layer', 'form', 'element'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;

        //错误提示
        @if(count($errors)>0)
            @foreach($errors->all() as $error)
                layer.msg("{{$error}}",{icon:2});
                @break
            @endforeach
        @endif

    })
</script>
</body>
</html>
