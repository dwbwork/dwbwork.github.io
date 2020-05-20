@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加图标</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.icon.store')}}" method="post">
                @include('admin.icon._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.icon._js')
@endsection