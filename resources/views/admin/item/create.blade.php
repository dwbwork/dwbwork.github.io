@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>发布商品</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.item.store')}}" method="post" >
                @include('admin.item._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.item._js')
@endsection
