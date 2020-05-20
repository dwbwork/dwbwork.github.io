@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新分类</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.item_category.update',['id'=>$info->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.item_category._form')
            </form>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.item_category._js')
@endsection