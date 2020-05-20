<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{session('configuration.site_title')}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="stylesheet" href="/static/public/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/public/assets/module/admin.css?v=315"/>
</head>
<body class="layui-layout-body">
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo">
                <img src="{{$logo}}"/>
                <cite>{{$web_name}}</cite>
            </div>
            <!-- 头部区域 -->


            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item" lay-unselect>
                    <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
                </li>

                {{--多系统模式--}}
                <li class="layui-nav-item layui-hide-xs layui-this" lay-unselect><a nav-bind="xt1"><b>系统</b></a></li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect><a nav-bind="xt2"><b>商城</b></a></li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect><a nav-bind="xt3"><b>物联网</b></a></li>
                <!-- 小屏幕下变为下拉形式 -->
                <li class="layui-nav-item layui-hide-sm layui-show-xs-inline-block" lay-unselect>
                    <a>更多</a>
                    <dl class="layui-nav-child">
                        <dd lay-unselect><a nav-bind="xt1"><b>系统</b></a></dd>
                        <dd lay-unselect><a nav-bind="xt2"><b>商城</b></a></dd>
                        <dd lay-unselect><a nav-bind="xt3"><b>物联网</b></a></dd>
                    </dl>
                </li>
                {{--多系统模式--}}
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
                {{--<li class="layui-nav-item" lay-unselect>
                    <a  layadmin-event="message" lay-text="消息中心">
                        <i class="layui-icon layui-icon-notice"></i>
                        <!-- 如果有新消息，则显示小圆点 -->
                        <span class="layui-badge-dot"></span>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>--}}
                <li class="layui-nav-item" lay-unselect>
                    <a id="clear" title="清理缓存"><i class="layui-icon layui-icon-fonts-clear"></i></a>
                </li>
                {{--<li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>--}}
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite>{{auth()->user()->nickname ?? auth()->user()->username}}</cite>
                    </a>
                    <dl class="layui-nav-child">
                       
                        <dd lay-unselect>
                            <a ew-href="{{route('admin.user.changeMyPasswordForm')}}">修改密码</a>
                        </dd> <dd><a href="{{route('admin.user.logout')}}">退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side">
            <div class="layui-side-scroll">
                {{--系统一的--}}
                <ul class="layui-nav layui-nav-tree arrow2" nav-id="xt1" lay-filter="admin-side-nav" lay-accordion="true"
                    style="margin-top: 15px;">
                    <li class="layui-nav-item">
                        <a href="javascript:;"  >
                            <i class="layui-icon layui-icon-home"></i>
                            &emsp;<cite>主页</cite>
                        </a>

                        <dl class="layui-nav-child" lay-tips="控制台">
                            <dd data-name="console">
                                <a lay-href="{{route('admin.index')}}">控制台</a>
                            </dd>
                        </dl>
                    </li>
                    <!--菜单-->
                    @foreach($menus as $menu)
                        @can($menu->name)
                    <li class="layui-nav-item" data-name="{{$menu->name}}">
                        <a><i class="layui-icon {{$menu->icon}}"></i>&emsp;<cite>{{$menu->display_name}}</cite></a>
                        @if($menu->childs->isNotEmpty())
                        <dl class="layui-nav-child">
                            @foreach($menu->childs as $subMenu)
                                @can($subMenu->name)
                            <dd data-name="{{$subMenu->name}}">
                                
                                <a  @if($subMenu->childs->isEmpty()) lay-href="{{route($subMenu->route) }}
                                    "
                                    @endif
                                    >{{$subMenu->display_name}}</a>
                                {{--三级菜单--}}
                                @if($subMenu->childs->isNotEmpty())
                                <dl class="layui-nav-child">
                                    @foreach($subMenu->childs as $subsMenu)
                                     <dd><a lay-href="{{ route($subsMenu->route) }}">{{$subsMenu->display_name}}</a></dd>
                                    @endforeach
                                </dl>
                                @endif
                                {{--三级菜单--}}
                            </dd>
                                @endcan
                            @endforeach
                        </dl>
                        @endif
                    </li>
                      @endcan
                   @endforeach
                    <!--菜单-->


                </ul>
                {{--系统二--}}
                <!-- 系统二的菜单，加layui-hide隐藏 -->
                <ul class="layui-nav layui-nav-tree layui-hide" nav-id="xt2" lay-filter="admin-side-nav"
                    style="margin: 15px 0;">
                    @foreach($menus2 as $menu)
                        @can($menu->name)
                        <li class="layui-nav-item" data-name="{{$menu->name}}">
                            <a><i class="layui-icon {{$menu->icon}}"></i>&emsp;<cite>{{$menu->display_name}}</cite></a>
                            @if($menu->childs->isNotEmpty())
                                <dl class="layui-nav-child">
                                    @foreach($menu->childs as $subMenu)
                                        @can($subMenu->name)
                                        <dd data-name="{{$subMenu->name}}">

                                            <a  @if($subMenu->childs->isEmpty()) lay-href="{{route($subMenu->route) }}
                                                    "
                                                    @endif
                                            >{{$subMenu->display_name}}</a>
                                            {{--三级菜单--}}
                                            @if($subMenu->childs->isNotEmpty())
                                                <dl class="layui-nav-child">
                                        @foreach($subMenu->childs as $subsMenu)
                                            <dd><a lay-href="{{ route($subsMenu->route) }}">{{$subsMenu->display_name}}</a></dd>
                                        @endforeach
                                </dl>
                                @endif
                                {{--三级菜单--}}
                                </dd>
                                @endcan
                                @endforeach
                                </dl>
                            @endif
                        </li>
                        @endcan
                    @endforeach
                </ul>

                <!-- 系统三的菜单，加layui-hide隐藏 -->
                <ul class="layui-nav layui-nav-tree layui-hide" nav-id="xt3" lay-filter="admin-side-nav"
                    style="margin: 15px 0;">
                    @foreach($menus3 as $menu)
                        @can($menu->name)
                        <li class="layui-nav-item" data-name="{{$menu->name}}">
                            <a><i class="layui-icon {{$menu->icon}}"></i>&emsp;<cite>{{$menu->display_name}}</cite></a>
                            @if($menu->childs->isNotEmpty())
                                <dl class="layui-nav-child">
                                    @foreach($menu->childs as $subMenu)
                                        @can($subMenu->name)
                                        <dd data-name="{{$subMenu->name}}">

                                            <a  @if($subMenu->childs->isEmpty()) lay-href="{{route($subMenu->route) }}
                                                    "
                                                    @endif
                                            >{{$subMenu->display_name}}</a>
                                            {{--三级菜单--}}
                                            @if($subMenu->childs->isNotEmpty())
                                                <dl class="layui-nav-child">
                                        @foreach($subMenu->childs as $subsMenu)
                                            <dd><a lay-href="{{ route($subsMenu->route) }}">{{$subsMenu->display_name}}</a></dd>
                                        @endforeach
                                </dl>
                                @endif
                                {{--三级菜单--}}
                                </dd>
                                @endcan
                                @endforeach
                                </dl>
                            @endif
                        </li>
                        @endcan
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- 侧边菜单 -->

        {{--主题内容--}}
        <div class="layui-body"></div>
        {{--主题内容--}}
    </div>
</div>
<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<!-- js部分 -->
<script type="text/javascript" src="/static/public/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
<script>
    layui.use(['index'], function () {
        var $ = layui.jquery;
        var index = layui.index;

        // 默认加载主页
        index.loadHome({
            menuPath: '{{route("admin.index")}}',
            menuName: '<i class="layui-icon layui-icon-home"></i>'
        });
        //清理缓存
        $('#clear').click(function(){

                $.get({
                    url:"{{route('admin.artisan.store')}}"
                    ,success:function(res) {
                        layer.msg('清理完成');
                        if(res.code == 1) {
                            setTimeout(function(){
                                location.href.reload(true);
                            },2000)
                        }
                    }
                })

        })
        $('#logout').click(function(){
            layer.confirm('真的要退出?',{icon: 3, title:'提示',anim: 2}, function(index){
                $.ajax({
                    url:"{:url('login/logout')}"
                    ,success:function(res) {
                        layer.msg(res.msg,{offset: '250px',anim: 4});
                        if(res.code == 1) {
                            setTimeout(function(){
                                location.href = res.url;
                            },2000)
                        }
                    }
                })
            })
        })

    });
</script>
</body>
</html>


