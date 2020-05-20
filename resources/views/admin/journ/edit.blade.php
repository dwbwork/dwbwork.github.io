@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新资讯</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.journ.update',['id'=>$journ->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.journ._form')
            </form>
        </div>
    </div>
@endsection
@section('script')

    @include('admin.journ._js')
@endsection
