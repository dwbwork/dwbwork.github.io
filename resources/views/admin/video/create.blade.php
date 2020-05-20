@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加视频</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.video.store')}}" method="post">
                @include('admin.video._form')
            </form>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.video._js')
@endsection