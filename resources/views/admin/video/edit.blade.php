@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新视频</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.video.update',['id'=>$info->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.video._form')
            </form>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.video._js')
@endsection