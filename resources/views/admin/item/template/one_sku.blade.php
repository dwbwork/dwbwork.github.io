 <!-- 单规格 -->
  
    <div class="layui-form-item" style="margin-top: 0;">
         
   <label for="" class="layui-form-label">挂牌价：</label>
        <div class="layui-input-block">
            <input type="number" name="line_price" id="title" value="{{$info['line_price']??old('line_price')}}"  placeholder="请输入商品挂牌价" class="layui-input" >
        </div>
        
    </div>

    <div class="layui-form-item" style="margin-top: 0;">
         
   <label for="" class="layui-form-label">实际售价：</label>
        <div class="layui-input-block">
            <input type="number" name="goods_price" id="title" value="{{$info['goods_price']??old('goods_price')}}"  placeholder="请输入实际售价" class="layui-input" >
        </div>
        
    </div>

    <div class="layui-form-item" style="margin-top: 0;">
         
   <label for="" class="layui-form-label">商品库存：</label>
        <div class="layui-input-block">
            <input type="text" name="inventory" id="title" value="{{$info['inventory']??old('inventory')}}"  placeholder="请输入商品库存" class="layui-input" >
        </div>
        
    </div>
