@extends('admin.base')

@section('content')
    <style>
        /** 应用快捷块样式 */
        .console-app-group {
            padding: 16px;
            border-radius: 4px;
            text-align: center;
            background-color: #fff;
            cursor: pointer;
        }

        .console-app-group .console-app-icon {
            width: 32px;
            height: 32px;
            line-height: 32px;
            margin-bottom: 6px;
            display: inline-block;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 32px;
            color: #69c0ff;
        }

        .console-app-group:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, .08);
        }

        /** //应用快捷块样式 */

        /** 小组成员 */
        .console-user-group {
            position: relative;
            padding: 10px 0 10px 60px;
        }

        .console-user-group .console-user-group-head {
            width: 32px;
            height: 32px;
            position: absolute;
            top: 50%;
            left: 12px;
            margin-top: -16px;
        }

        .console-user-group .layui-badge {
            position: absolute;
            top: 50%;
            right: 8px;
            margin-top: -10px;
        }

        .console-user-group .console-user-group-name {
            line-height: 1.2;
        }

        .console-user-group .console-user-group-desc {
            color: #8c8c8c;
            line-height: 1;
            font-size: 12px;
            margin-top: 5px;
        }

        /** 卡片轮播图样式 */
        .admin-carousel .layui-carousel-ind {
            position: absolute;
            top: -41px;
            text-align: right;
        }

        .admin-carousel .layui-carousel-ind ul {
            background: 0 0;
        }

        .admin-carousel .layui-carousel-ind li {
            background-color: #e2e2e2;
        }

        .admin-carousel .layui-carousel-ind li.layui-this {
            background-color: #999;
        }

        /** 广告位轮播图 */
        .admin-news .layui-carousel-ind {
            height: 45px;
        }

        .admin-news a {
            display: block;
            line-height: 60px;
            text-align: center;
            border-radius: 4px;
        }
    </style>
    </head>
    <body>
    <!-- 正文开始 -->
    <div class="layui-fluid ew-console-wrapper">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增用户量<span class="layui-badge layui-badge-green pull-right">日</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$user_number_today}}</p>
                        <p>总用户量<span class="pull-right">{{$user_number}}</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        销售额<span class="layui-badge layui-badge-blue pull-right">月</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font"><span style="font-size: 26px;line-height: 1;">¥</span>{{$order_total_month}}</p>
                        <p>总销售额<span class="pull-right">{{$order_total}}元</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        订单量<span class="layui-badge layui-badge-red pull-right">周</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$order_number}}</p>
                        <p>退款量<span class="pull-right">{{$order_refund_number}}</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        机构数量
                    <span class="icon-text pull-right" lay-tips="指标说明" lay-direction="4" lay-offset="5px,5px">
                        <i class="layui-icon layui-icon-tips"></i>
                    </span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$store}} <span style="font-size: 24px;line-height: 1;">位</span></p>
                        <p>总销售额<span class="pull-right">{{$store_order}} 元</span></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 快捷方式 -->
     {{--   <div class="layui-row layui-col-space15">
            <div class="layui-col-sm6" style="padding-bottom: 0;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <a lay-href="{{route('admin.wxusers')}}">
                            <i class="console-app-icon layui-icon layui-icon-group"
                               style="font-size: 26px;padding-top: 3px;margin-right: 6px;"></i>

                                <div class="console-app-name">控制台</div></a>

                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-chart" style="color: #95de64;"></i>
                            <div class="console-app-name">分析</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-cart" style="color: #ff9c6e;"></i>
                            <div class="console-app-name">网上课程</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-form"
                               style="color: #b37feb;font-size: 30px;"></i>
                            <div class="console-app-name">订单</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6" style="padding-bottom: 0;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-layer"
                               style="color: #ffd666;font-size: 34px;"></i>
                            <div class="console-app-name">票据</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-email"
                               style="color: #5cdbd3;font-size: 36px;"></i>
                            <div class="console-app-name">消息</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-note"
                               style="color: #ff85c0;font-size: 28px;"></i>
                            <div class="console-app-name">标签</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-slider" style="color: #ffc069;"></i>
                            <div class="console-app-name">配置</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}

        <div class="layui-row layui-col-space15">
            <div class="layui-col-lg8 layui-col-md7">
                <div class="layui-card">
                    <div class="layui-card-header">最新动态</div>
                    <div class="layui-card-body" style="padding: 17px 15px;">
                        <table id="consoleHotTb1" lay-filter="consoleHotTb1"></table>
                    </div>
                </div>
            </div>
            <div class="layui-col-lg4 layui-col-md5">
                <div class="layui-card-header">热门商家</div>
                @foreach ($store_list as $item)
                <div class="layui-card">
                    <div class="layui-card-header">{{$item->address}}</div>
                    <div class="layui-card-body">
                        <div class="console-user-group">
                            <img src="{{$item->thumb}}" class="console-user-group-head"/>
                            <div class="console-user-group-name">{{$item->name}}</div>
                            <div class="console-user-group-desc">Designer</div>
                            <span class="layui-badge layui-badge-green">在线</span>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('script')
        <!-- js部分 -->
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>

    <script>

        layui.use(['layer', 'carousel', 'table'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var carousel = layui.carousel;
            var table = layui.table;
            var device = layui.device();

            // 渲染轮播
            carousel.render({
                elem: '.layui-carousel',
                width: '100%',
                height: '60px',
                arrow: 'none',
                autoplay: true,
                trigger: device.ios || device.android ? 'click' : 'hover',
                anim: 'fade'
            });
            table.render({
                elem: '#consoleHotTb1'
                , autoSort: false
                , height: 500
                , url: "{{ route('admin.topic.data') }}" //数据接口
                , page: true //开启分页
                , cols: [[ //表头
                    {checkbox: true, fixed: true}
                    , {field: 'id', title: 'ID', sort: true, width: 80}

                    , {field: 'title', title: '帖子标题'}
                   , {field: 'views', title: '点击量'}
                    , {field: 'created_at', title: '创建时间', width: 160}
                    /*, {field: 'updated_at', title: '更新时间'}*/

                    ]]
            });
            // 渲染表格
            /*table.render({
                elem: '#consoleHotTb1',
                data: [
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333},
                    {searchNum: 666, userNum: 333}
                ],
                page: {limit: 12},
                cols: [[
                    {type: 'numbers', fixed: 'left'},
                    {
                        title: '搜索关键词', templet: function (d) {
                        return '<span class="layui-text"><a href="javascript:;">搜索关键词</a></span>';
                    }
                    },
                    {field: 'searchNum', title: '点击次数', sort: true},
                    {field: 'userNum', title: '转发数', sort: true},
                ]],
                skin: 'line'
            });*/

        });
    </script>
@endsection