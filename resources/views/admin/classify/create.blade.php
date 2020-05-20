@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加设备分类</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.classify.store')}}" method="post">
                @include('admin.classify._form')
            </form>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.classify._js')
@endsection