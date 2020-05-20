@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新分类</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.journ_cate.update',['id'=>$info->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.journ_cate._form')
            </form>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.journ_cate._js')
@endsection