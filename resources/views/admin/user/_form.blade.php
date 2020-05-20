{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">关联合作商</label>

    <div class="layui-input-inline">
        <select name="company_id" lay-search  lay-filter="parent_id">
            <option value="0">请选择</option>
            @foreach($company as $first)
                <option value="{{ $first->id }}" @if(isset($user->company_id)&&$user->company_id==$first->id) selected @endif>{{ $first->name }}</option>

            @endforeach
        </select>
        <p id="process"> <span style="color: red ">此项为合作商账号必填</span></p>
    </div>

</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">用户名</label>
    <div class="layui-input-inline">
        <input type="text" maxlength="16" name="username" value="{{ $user->username ?? old('username') }}" lay-verify="required" placeholder="请输入用户名" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">昵称</label>
    <div class="layui-input-inline">
        <input type="text" maxlength="16" name="nickname" value="{{ $user->nickname ?? old('nickname') }}" lay-verify="required" placeholder="请输入昵称" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">邮箱</label>
    <div class="layui-input-inline">
        <input type="email" name="email" value="{{$user->email??old('email')}}" lay-verify="required|email" placeholder="请输入Email" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">手机号</label>
    <div class="layui-input-inline">
        <input type="text" name="phone" value="{{$user->phone??old('phone')}}" lay-verify="required|phone"  placeholder="请输入手机号" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password" placeholder="请输入密码" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">确认密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password_confirmation" placeholder="请输入密码" class="layui-input">
    </div>
</div>

{{--
<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.user')}}" >返 回</a>
    </div>
</div>--}}
