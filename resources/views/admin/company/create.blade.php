@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加合作商</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.company.store')}}" method="post">
                @include('admin.company._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.company._js')
@endsection