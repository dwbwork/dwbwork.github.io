{{csrf_field()}}
 <style>
        [lay-filter="formQA"] .layui-form-item, .layui-inline {
            margin-bottom: 0;
            margin-top: 20px;
        }

        .qa-xx-item {
            padding-left: 25px;
            margin-bottom: 20px;
        }

        .qa-xx-item-title {
            position: absolute;
            left: 0;
            line-height: 38px;
        }

    </style>
    <style>
    #demo2{
        display: flex;
        align-items: center;
    }
    #demo2 li .operate{ color: #000; display: none;}
    #demo2 li .close{position: absolute;top: 5px; right: 5px;cursor:pointer;}
    #demo2 li:hover .operate{ display: block;}
    .operate{
        position: absolute;
        right: 0;
        top: -9px;
        color: black;
        font-size: 34px;
        /* border: 1px solid red; */
        font-weight: bold;
    }
    .item_img{
        position: relative;margin-right: 10px;
    }
    .attribute{
        width: 100%;
        border: 1px solid #e6e6e6;
        margin-top:20px;
    }
    .atr{
        height: 50px;
        border: 1px solid #e6e6e6;
    }
    .ath{
        width: 100px; border-left: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6;
    }
    .sl{
        width: 180px;
        margin: 0 auto;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0;
       display: block;
        white-space: nowrap;
    }
    .atd{
        display: flex;
        text-align: center;
        height: 38px;
        justify-content: center;
        align-items: center;
    }
    table input{
        text-align: center;
        border: none!important;
    }
    table td{
        border-right: 1px solid #e6e6e6;
        border-bottom: 1px solid #e6e6e6;
    }
    table td:last-child{
        border-right: none;
    }
    .divs{display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 0;
        box-sizing: border-box;
    }

    #progress{
        width: 300px;
        height: 20px;
        background-color:#f7f7f7;
        box-shadow:inset 0 1px 2px rgba(0,0,0,0.1);
        border-radius:4px;
        background-image:linear-gradient(to bottom,#f5f5f5,#f9f9f9);
    }

    .finish{
        float:left;
        border-radius: 10px;
        background-color: #149bdf;
        background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);
        background-size:40px 40px;
        display: inline-block;
        height: 20px;
    }
</style>
<!-- 正文开始 -->

<form class="layui-form" lay-filter="formQA" style="max-width: 960px;">
    <input type="hidden" name="id" value="{{@$info['id']}}"/>

<div class="layui-tab">
  <ul class="layui-tab-title">
    <li class="layui-this">基本信息</li>
    <li>图片</li>
    <li>规格属性</li>
    
   
  </ul>
  <div class="layui-tab-content">
    <!-- 基本信息 -->
    <div class="layui-tab-item layui-show">
        <div class="layui-form-item" style="margin-top: 0;">
         
        <label for="" class="layui-form-label">商品标题：</label>
        <div class="layui-input-block">
            <input type="text" name="goods_name" id="title" value="{{$info['goods_name']??old('goods_name')}}" lay-verify="required" placeholder="请输入商品标题" class="layui-input" >
        </div>
        
    </div>
   
    
        <div class="layui-form-item" style="margin-top: 0;">
         
        <label for="" class="layui-form-label">商品货号：</label>
        <div class="layui-input-block">
            <input type="text" name="goods_no" id="title" value="{{$info['goods_no']??old('goods_no')}}" lay-verify="required" placeholder="请输入商品货号" class="layui-input" >
        </div>
        
    </div>
     <div class="layui-form-item">
    <label for="" class="layui-form-label">主&nbsp&nbsp&nbsp&nbsp图：</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
           <button type="button" id="btnAddUser" class="layui-btn layui-btn-sm showFilesBox" ><i class="layui-icon">&#xe67c;</i>图片库</button>
            <div class="layui-upload-list" >
                <ul class="layui-upload-box layui-clear">
                    @if(isset($info['thumb'])) 
                        <li><img style="width:100px;height:60px" src="{{ $info['thumb'] }}" /></li>
                    @endif
                </ul>
                <input type="hidden" name="thumb" class="layui-upload-input" value="{{ $info['thumb']??'' }}">
            </div>
        </div>
    </div>
</div>

<!-- 图片库 -->
<script type="text/html" id="modelUser">
     
</script>
<!-- 图片库 -->
    <div class="layui-form-item" style="margin-top: 0;">
        <div class="layui-inline">
            <label class="layui-form-label">商品分类：</label>
            <div class="layui-input-inline">
            <select name="category_id">
                <option value="0"></option>
         
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(isset($info['category_id'])&&$info['category_id']==$category->id)selected @endif >{{ $category->name }}</option>
                    @if(isset($category->allChilds)&&$category->allChilds->isNotEmpty())
                        @foreach($category->allChilds as $child)
                            <option value="{{ $child->id }}" @if(isset($info->category_id)&&$info['category_id']==$child->id)selected @endif >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
                            @if(isset($child->allChilds)&&$child->allChilds->isNotEmpty())
                                @foreach($child->allChilds as $third)
                                    <option value="{{ $third->id }}" @if(isset($info['category_id'])&&$info['category_id']==$third->id)selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
              
            </select>
                    
        </div>
        </div>
    </div>
    <br>

</div>
     <!-- 基本信息 -->

   
    
      <!-- 图片 -->
<div class="layui-tab-item">
       
<div class="layui-upload">
			<label class="layui-form-label">商品轮播图</label>
			<button type="button" class="layui-btn" id="test2">多图片上传</button>
			<span style="color: #FFB800">（图片尺寸：375px * 370px）</span>
			<blockquote class="layui-elem-quote layui-quote-nm " style="margin-top: 10px;">
				预览图：
				<div class="layui-upload-list" id="demo2">
					@if(isset($info['image']))
						@foreach($info['image'] as $im)
					<li class="item_img"><div class="operate"></i><i  class="close layui-icon"></i>
                    </div>
						<img src="{{$im??''}}" class="layui-upload-img" width="60">
						<input type="hidden" name="image[]" value="{{$im??''}}">
					</li>
						@endforeach
					@endif

				</div>
			</blockquote>
		</div>
		<div class="layui-form-item">
				<label class="layui-form-label">商品内容：</label>
				<div class="layui-input-block">
					<textarea name="content" id="qaContent">
          {!! @$info['content']??old('content') !!}               
                    </textarea>
				</div>
		</div>
</div>
      <!-- 图片 -->
       <!-- 规格属性 -->
<div class="layui-tab-item">
        <div class="layui-form-item">
            <label class="layui-form-label">规格类型：</label>
            <div class="layui-input-block">
                <input type="radio" lay-filter="raQT" name="spec_type" value="1" title="单规格" @if(!isset($info) || (isset($info) && $info['spec_type']==1)) checked @endif >
                <input type="radio" lay-filter="raQT" name="spec_type" value="2" title="多规格" @if((isset($info) && $info['spec_type']==2)) checked @endif >
                
            </div>
        </div>
       

       
 <span id="qaRI1" @if((isset($info) && $info['spec_type'] !=1)) style="display:none" @endif>
   @include('admin.item.template.one_sku')
</span>
 <span @if(!isset($info) || (isset($info) && $info['spec_type'] !=2)) style="display:none" @endif id="qaRI2">
   @include('admin.item.template.sku')
</span>

</div>



</div>
</div>

<div class="layer-footer" style="position: fixed; bottom: 0;width:100%;height:50px">
<div class="layui-form-item">
        <div class="layui-input-block text-center">
            
            <a class="layui-btn layui-btn-primary" href="{{route('admin.item')}}" >返 回</a>
            <button class="layui-btn" lay-filter="formSubmitAQ" lay-submit>&emsp;提交&emsp;</button>
        </div>
</div>
</div>
</form>
