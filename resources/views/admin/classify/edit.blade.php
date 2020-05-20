@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑设备</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.classify.update',['id'=>$class->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.classify._form')
            </form>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.classify._js')
@endsection