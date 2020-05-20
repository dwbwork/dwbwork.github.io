@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新图标</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.icon.update',['id'=>$info->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.icon._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.icon._js')
@endsection