@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加设备</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.plant.store')}}" method="post">
                @include('admin.plant._form')
            </form>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.plant._js')
@endsection