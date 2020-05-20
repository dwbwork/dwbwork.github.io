<head>
<script src="/static/admin/layuiadmin/jqueryskutable/Convert_Pinyin.js" type="text/javascript" charset="utf-8"></script>

		<style>
	        #sku-wrap, #sku-value-wrap{
	            display: flex;
	            align-items: start;
	            float: left;
	        }
	        #sku-wrap input,#sku-value-wrap input{
	            background: transparent;
	            width: 60px;
	            text-align: center;
	            border: 1px solid #E6E6E6;
	            margin-right: 20px;
	            border-radius: 2px;
	            padding: 10px 0;
	        }
	        #sku-wrap .sku-active{
	            border: 1px solid #1F9FFF;
	            color: #1F9FFF;
	        }
	        #sku-value-wrap input{
	            /*display: none;*/
	        }
	        #my-table{
	           
	            margin-left: 94px;
	        }
	        #my-table input{
	            border: 0;
	        }
	        #my-table td{
	            white-space: nowrap;
	        }
	        /*.layui-form-item{
	            width: 100%;

	        }*/
	        .delete-icon{
	            display: inline-block;
	            width: 12px;
	            height: 1px;
	            background: #C2C2C2;
	            transform: rotate(45deg);
	            -webkit-transform: rotate(45deg);
	            position: relative;
	            right: 33px;
	            top: 5px;
	            cursor: pointer;
	            z-index: 9999;
	        }
	        .delete-icon:after{
	            content: '';
	            display: block;
	            width: 12px;
	            height: 1px;
	            background: #C2C2C2;
	            transform: rotate(-90deg);
	            -webkit-transform: rotate(-90deg);
	            cursor: pointer;
	            position: relative;
	            z-index: 9999;
	        }
	    </style>
	    <style type="text/css">
		.spec{
			    display: flex;
    width: 100%;
        margin: 20px 0;
		}
	</style>
	</head>
	
	<body >
		
			<div class="layui-form-item" style="margin-top: -30px" class="layui-input-block" >
				<label class="layui-form-label" >规格:</label>
				
				<div id="sku-wrap" style="flex-wrap:wrap;border:1px solid #E6E6E6;padding: 20px;margin-right: 0;min-width: 190px;">
				
				@if(isset($sku))
                              @foreach($sku as $sk)
                      
                <div class="sp" style="flex-wrap:wrap;border:0px solid #E6E6E6;padding: 20px;margin-right: 0;min-width: 190px;">
						    <span class="sku_m">
							<input type="button" data="{{$sk->spec_id}}" class="sku {{$sk->active}} sku-choose {{$sk->full_name}}" name="{{$sk->full_name}}" value="{{$sk->spec_name}}"><span class="delete-icon" onclick="del(this,'{{$sk->spec_id}}')"></span>
		                    </span>

						<div class="spec sku-value-wrap">
							@if(isset($sk->spec_value))
                              @foreach($sk->spec_value as $sk_va)
							<input type="button" data-pid="{{$sk->spec_id}}" data="{{$sk_va->spec_value_id}}" class="sku-value {{$sk->full_name}} {{$sk_va->active}} 
                            
							" name="{{$sk_va->spec_value_id}}" value="{{$sk_va->spec_value}}"><span class="delete-icon" onclick="del(this,'{{$sk_va->spec_value_id}}')"></span>
							@endforeach
                            @endif
						     <div class="layui-input-inline">
						        <input type="text" style="width: 200px" name="input" placeholder="请输入规格值"  autocomplete="off" class="layui-input">
						     </div>
						      <button style="float: left;" type="button" onclick="add_spec(this,'{{$sk->full_name}})" class="layui-btn layui-btn-normal" >添加</button>
						     
						</div>
		        </div>      

                              @endforeach
               @endif
				<div class="layui-input-inline" style="width: 100%;margin-top: 20px">
					<button class="layui-btn" type="button" id="skuAdd">添加规格</button>
				</div>
				
				<div class="layui-input-inline" id="skuModel" style="display: none;border: 1px solid #E6E6E6;padding: 20px;margin-right: 0;min-width: 190px;">
					<input type="text" name="skuName" id="skuName"  class="layui-input" placeholder="请输入规格名" style="width:180px;margin-bottom: 20px;" autocomplete="off">
					<input type="text" name="skuValueName" id="skuValueName"  class="layui-input" placeholder="请输入规格值" style="width:180px;margin-bottom: 20px;" autocomplete="off">
					<button type="button" class="layui-btn layui-btn-normal" id="skuConfirm">确认</button>
					<button type="button" class="layui-btn layui-btn-danger" id="skuCancel">取消</button>
				</div>
				</div>
			</div>
			<!-- 批量设置 -->
<div class="layui-form-item layui-card-body" style="margin-left: 94px">
				<!-- 批量设置 -->
				
                <table class="layui-table">
						<thead>
						<tr >
							<th >挂牌价</th>
							<th >实际售价</th>
							
							<th >库存</th>
							
						</tr>
						</thead>
						<tbody>

							<td><input type="number"  onchange="more(this)" placeholder="请输入商品挂牌价" class="layui-input " data-id="line_price"/></td>
							<td><input type="number"  data-id="goods_price" onchange="more(this)"  placeholder="请输入商品售价" class="layui-input "/></td>
							<td><input type="number"  onchange="more(this)" placeholder="请输入商品库存" class="layui-input " data-id="inventory"/></td>
							
						</tbody>
					</table>
				<hr class="layui-bg-orange">
</div>
			<!-- 批量设置 -->
				<div class="layui-form-item layui-card-body" id="my-table" @if(@$info['spec_type'] !=2) style="display:none" @endif>

					<table class="layui-table" kin="nob" width="100%" style="margin-bottom: 100px">
						<thead>
						<tr id="th">
							@if(isset($list))
                              @foreach($list as $im)
                              <th>{{$im[0]['category']['spec_name']}}</th>
                              @endforeach
                            @endif
                              
							<th>挂牌价格</th><th>实际售价</th><th>库存</th><th>操作</th>
						</tr>
						</thead>
						<tbody id="tbody">
							@if(isset($info['item_sku']))
                              @foreach($info['item_sku'] as $im)
	                              @foreach($im['spec'] as $imn)
	                              <th>{{$imn}}</th>
	                              @endforeach
<td><input type="number" class="line_price" name="spec[{{$im['spec_sku_id']}}][line_price]" lay-verify="required" placeholder="请输入商品挂牌价" value="{{$im['line_price']}}"class="layui-input"/></td>
		                    
<td><input type="number" class="goods_price" name="spec[{{$im['spec_sku_id']}}][goods_price]" lay-verify="required" placeholder="请输入商品单价" value="{{$im['goods_price']}}" class="layui-input"/></td>
<input type="hidden" class="spec_sku_id" value="{{$im['spec_sku_id']}}" name="spec[{{$im['spec_sku_id']}}][spec_sku_id]"/>

<td><input type="number" class="inventory" name="spec[{{$im['spec_sku_id']}}][inventory]" lay-verify="required" placeholder="请输入商品库存" value="{{$im['inventory']}}" class="layui-input"/></td>

<td> <button type="button" onclick="del_td(this)" class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon" ></i> 删除</button></td>
		                    </tr>
                              @endforeach
							@endif
						</tbody>
					</table>
				</div>


		
		<script src="/static/admin/layuiadmin/jqueryskutable/Convert_Pinyin.js"></script>
		<script src="https://www.jq22.com/jquery/jquery-1.10.2.js"></script>
		    <script type="text/javascript">
		        $(document).ready(function(){
		        	//规格名删除
		          /*  $('#sku-wrap').on('click', '.delete-icon', function (e) {
		                
		                event.stopPropagation();    //  阻止事件冒泡
		                var skuName = $(this).prev().val();
		                var fullName = pinyin.getFullChars(skuName);
		                
		                var objs = document.getElementsByName(fullName);
		                
		                for(var i = 0;i<=objs.length;i++){
		                    objs[0].nextSibling.remove();
		                    objs[0].remove();
		                    console.log(i)
		                }
		                $(this).prev().remove();
		                $(this).remove();
		
		                event.stopPropagation();    //  阻止事件冒泡
		                combination();
		            });
		            //规格值删除
		            $('.sku-value-wrap').on('click', '.delete-icon', function (e) {
		            	console.log(e);return;
		                $(this).prev().remove();
		                $(this).remove();
		
		                event.stopPropagation();    //  阻止事件冒泡
		                combination();
		            });*/


		        });
		        


		        function del(e,ids){
		        	var hs=$(e);
		        	if($(e).prev().is('.sku-choose')){
		        		
		        		 $.post("{{ route('admin.item_spec.destroy') }}", {
                                _method: 'delete',
                                ids: [ids]
                            }, function (res) {
                               
                                if (res.code == 200) {
                                    layer.msg(res.msg, {icon: 1,time:1000});
                                        hs.parents('.sp').remove();
                                   
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            });

                      
		        	}else{
		        		$.post("{{ route('admin.item_spec_value.destroy') }}", {
                                _method: 'delete',
                                ids: [ids]
                            }, function (res) {
                                
                                if (res.code == 200) {
                                    layer.msg(res.msg, {icon: 1,time:1000});
                                      hs.prev().remove();
		        	                  hs.remove();
                                    
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            });
		        		
		        	}
		        	
		        	combination();
		        } 
		        var firstTime = false;
		        // 添加规格按钮
		        $('#skuAdd').click(function () {
		            var skeLen = $('#sku-wrap').find('.sku_m').children().length;
		           
		            if(skeLen==0){
		                firstTime = true;
		            }else {
		                firstTime = false;
		            }
		            $(this).hide();
		            $('#skuModel').show();
		        });
		        //  取消添加规格按钮
		        $('#skuCancel').click(function () {
		            $('#skuName').val('');
		            $('#skuModel').hide();
		            $('#skuAdd').show();
		        });
		         
		        //批量添加方法
		        function more(e){
		        	var data = $(e).attr('data-id');
		        	$('.'+data).val($(e).val());

		        } 
                //删除单行表格
                function del_td(e){
                	var data = $(e).parents('tr');
                	data.remove();
                }
  


		        //添加规格值方法
                function add_spec(e,fullName){
                	var spec = $(e).prev().find('input').val();
                	var pid= $(fullName).attr('data');
                	var pingyin = $(fullName).attr('name');
                	if($(e).parents('.spec').find('input').val()==spec){
                		layer.msg('规格值已存在');
		                return;
                	}
                      var fh=$(e);
                   $.post("{{route('admin.item_spec_value.store')}}",{spec_id:pid,spec_value:spec}, function (res) {
                       layer.closeAll('loading');
                       if (res.code == 200) {
                           layer.msg(res.msg, {icon: 1,time:1000});
                           fh.parents('.spec').prepend('<input type="button" data="'+res.data.spec_value_id+'"class="sku-value '+pingyin+'" name="'+res.data.spec_value_id+'" value="'+spec+'"><span class="delete-icon" onclick="del(this,'+res.data.spec_value_id+')"></span>');
                       } else {
                           layer.msg(res.msg, {icon: 2});
                            return;
                       }
                   }, 'json');

                	
                };

		        //  添加规格确认按钮
		        var fullName = '';
		        $('#skuConfirm').click(function () {
		
		            
		            var skuName = $('#skuName').val();//规格名
		            var skuValueName = $('#skuValueName').val();//规格值
		            if(skuName == '' || skuValueName == ''){
		                layer.msg('请输入规格名或规格值');
		                return;
		            }
		            //获取全写拼音（调用js中方法）
		            fullName = pinyin.getFullChars(skuName);
		            //  判断规格是否已存在
		            if(ifSkuExit(fullName)){
		                layer.msg('此规格已存在！');
		                return;
		            }
                     /*异步添加规格和规格值*/
		            layer.load(2);
		            var fh=$(this);
                   $.post("{{route('admin.item_spec.store')}}",{full_name:fullName,spec_name:skuName,spec_value:skuValueName}, function (res) {
                       layer.closeAll('loading');
                       if (res.code == 200) {
                       	   
                           layer.msg(res.msg, {icon: 1});
                            fh.parents('#sku-wrap').prepend('<div class="sp" style="flex-wrap:wrap;border:0px solid #E6E6E6;padding: 20px;margin-right: 0;min-width: 190px;">'+
						    '<span class="sku_m">'+
							'<input type="button" data="'+res.data.spec_id+'" class="sku sku-active sku-choose '+fullName+' " name="'+fullName+'" value="'+skuName+'"><span class="delete-icon" onclick="del(this,'+res.data.spec_value_id+')"></span>'+
		                    '</span>'+

						'<div class="spec sku-value-wrap">'+
							'<input type="button" data-pid="'+res.data.spec_id+'" data="'+res.data.spec_value_id+'" class="sku-value '+fullName+'" name="'+res.data.spec_value_id+'" value="'+skuValueName+'"><span class="delete-icon" onclick="del(this,'+res.data.spec_value_id+')"></span>'+
						     '<div class="layui-input-inline">'+
						        '<input type="text" style="width: 200px" name="input" placeholder="请输入规格值"  autocomplete="off" class="layui-input">'+
						     '</div>'+
						      '<button style="float: left;" type="button" onclick="add_spec(this,'+fullName+')" class="layui-btn layui-btn-normal" >添加</button>'+
						     
						'</div>'+
		                '</div>');
		                 ;
		                   $(this).parents('.layui-form-item').find('.spec').show();
				            $('#skuName').val('');
				            $('#skuValueName').val('');
				            $('#skuModel').hide();
				            $('#skuAdd').show();
                       } else {
                           layer.msg(res.msg, {icon: 2});
                            return;
                       }
                   }, 'json');
                
		        });
		
		        //  切换sku
		        $(document).on('click', '.sku', function(){
		           
		            event.stopPropagation();    //  阻止事件冒泡
		            //$('.sku-value').hide();
		            $('#sku-value-wrap .delete-icon').hide();
		            if($(this).hasClass('sku-active')){
		                $(this).removeClass('sku-active');
		            }else {
		                /*var obj = $('.sku-active');
		                if (obj.length==2){
		                    layer.msg('只能选择两种规格');
		                    return;
		                }*/
		                $(this).addClass('sku-active');
		            }
		            $(this).addClass('sku-choose').siblings().removeClass('sku-choose');
		            var skuName = $(this).val();
		            fullName = pinyin.getFullChars(skuName);
		            
		            //var objs = document.getElementsByName(fullName);
		            var objs = $('.sku-value.'+fullName );
		            
		            for(var i =0;i<objs.length;i++){
		                objs[i].style.display="block";
		                objs[i].nextSibling.style.display="block";
		            }
		            combination();
		        });
		        
		        //  切换规格值
		        $(document).on('click', '.sku-value', function(){
		            
		            event.stopPropagation();    //  阻止事件冒泡
		            //$('.sku-value').hide();
		            //$('#sku-value-wrap .delete-icon').hide();
		            if($(this).hasClass('sku-active')){
		                $(this).removeClass('sku-active');
		            }else {
		               
		                $(this).addClass('sku-active');
		            }
		            $(this).addClass('sku-choose').siblings().removeClass('sku-choose');
		            var skuName = $(this).attr('name');
		            fullName = pinyin.getFullChars(skuName);
		            
		            //var objs = document.getElementsByName(fullName);
		            var objs = $('.sku-value.'+fullName );
		            
		            for(var i =0;i<objs.length;i++){
		                objs[i].style.display="block";
		                objs[i].nextSibling.style.display="block";
		            }
		            combination();
		        });
		
		        // 添加规格值按钮
		        $('#skuValueAdd').click(function () {
		            var skeLen = $('#sku-wrap').children().length;
		            if(skeLen==0){
		                layer.msg('请添加规格');
		                return;
		            }
		            var skeLen = $('#sku-wrap .sku-active').length;
		            if(skeLen==0){
		                layer.msg('请选择规格');
		                return;
		            }
		            $(this).hide();
		            $('#skuValueModel').show();
		        });
		        //  取消添加规格值按钮
		        $('#skuValueCancel').click(function () {
		            $('#skuValueName').val('');
		            $('#skuValueModel').hide();
		            $('#skuValueAdd').show();
		        });
		
		        //  添加规格值确认按钮
		        $('#skuValueConfirm').click(function () {
		            var skuName = $('.sku-choose').val();
		            fullName = pinyin.getFullChars(skuName);
		            var skuValueName = $('#skuValueName').val();
		            if(skuValueName == ''){
		                layer.msg('请输入规格名');
		                return;
		            }
		            //获取全写拼音（调用js中方法）
		            var fullValueName = pinyin.getFullChars(skuValueName);
		            //  判断规格值是否已存在
		            if(ifSkuExit(fullValueName)){
		                layer.msg('此规格已存在！');
		                return;
		            }
		            $('#sku-value-wrap').append('<input type="text" class="sku-value '+fullValueName+'" name="'+fullName+'" value="'+skuValueName+'" readonly><span class="delete-icon"></span>');
		            $('#skuValueName').val('');
		            $('#skuValueModel').hide();
		            $('#skuValueAdd').show();
		            combination();
		            $('#my-table').show();
		            combination();
		        });
		
		
		        function ifSkuExit(name){
		            var len = document.getElementsByClassName(name).length;
		            if(len==0){
		                return false;
		            }else {
		                return true;
		            }
		        }
		
		        // 组合数组
		
		        function combination () {
		            $('#tbody').html('');
		            $('#th').html('');
		            var arr = [];
		            var array = [];
		            var spec_value =[];
		            var skuObjs = $('.sku.sku-active');
		            //console.log(skuObjs,arr,5656)
		            for (var i = 0;i<skuObjs.length;i++){
		                var sku = skuObjs[i].value;
		                var py = pinyin.getFullChars(sku);
                        spec_value[i] = [];
		                arr[i] = [];
		                var skuValueObjs = $('.sku-value.sku-active.'+py );
		              
	                   for(var j =0;j<skuValueObjs.length;j++){
			                    array[j] = [];
			                    array[j] = skuValueObjs[j].value;
			                    arr[i].push(array[j]);//规格值拼接
			                    
			                    spec_value[i].push(skuValueObjs[j].name)//规格值id拼接
			                }
		                 
		            }
		            generateGroup(arr,spec_value);
		        }
		
		
		
		        // 循环组合
		        function generateGroup(sendarr,spec_value) {
		        	//console.log(sendarr)
		        	 //var arr = [...sendarr];//防止篡改
		        	// var arr = JSON.parse(JSON.stringify(sendarr))
		        	var arr = []
		        	for(let item of sendarr) {
		        		if(item.length > 0) {
		        			arr.push(item)
		        		}
		        	}
		        	
		        	var spec_arr = []
		        	for(let item of spec_value) {
		        		if(item.length > 0) {
		        			spec_arr.push(item)
		        		}
		        	}
		            var tablehtml = '';
		            var skuObjs = $('.sku.sku-active');
		            
		            for (var i = 0;i<skuObjs.length;i++){
		                // var sku = skuObjs[i].value;
		                if($('.sku-active.'+skuObjs[i].name).length>1){
		                	// $('#th').append('<th>'+sku+'</th>');
		                tablehtml += '<th>'+skuObjs[i].value+'</th>';
		                }
		                
		            }
		            tablehtml += '<th>挂牌价格</th><th>实际售价</th><th>库存</th><th>操作</th>';
		            $('#th').html(tablehtml);
		                // $('#th').append('<th>价格</th><th>库存</th>');
					
		            //初始化结果为第一个数组
		            var result= arr[0];
		            var spec = spec_arr[0];
		            //从下标1开始遍历二维数组
		            for(var i=1;i<arr.length;i++){
		                //使用临时遍历替代结果数组长度(这样做是为了避免下面的循环陷入死循环)
		                var size= result.length;
		                //根据结果数组的长度进行循环次数,这个数组有多少个成员就要和下一个数组进行组合多少次
		                for(var j=0;j<size;j++){
		                    //遍历要进行组合的数组
		                    for(var k=0;k<arr[i].length;k++){
		                        //把组合的字符串放入到结果数组最后一个成员中
		                        //这里使用下标0是因为当这个下标0组合完毕之后就没有用了，在下面我们要移除掉这个成员
		                        result.push(result[0]+","+arr[i][k]);
                                
                                spec.push(spec[0]+"_"+spec_arr[i][k]);
		                        // $('#tbody').append('<tr><td>'+result[0]+'</td><td>'+arr[i][k]+'</td><td><input/> </td></tr>')
		                    }
		                    //当第一个成员组合完毕，删除这第一个成员
		                    result.shift();
		                    spec.shift();
		                }
		            }
		            //打印结果
		            //console.log(result,spec);

		            if(typeof(result)!="undefined"){
		                
		                for(var i=0;i<result.length;i++){
		                    var html = '';
		                    html += '<tr>';
		                    var arr = result[i].split(',');

		                    var sku_arr = spec[i];
		                    
		                    for (var j=0;j<arr.length;j++){
		                    	//console.log(arr[j]);
		                        html += '<td>'+arr[j]+'</td>'//循环一列组合
		                    }
		                    html += '<td><input type="number" class="line_price" name="spec['+sku_arr+'][line_price]" lay-verify="required" placeholder="请输入商品挂牌价" class="layui-input"/></td>'+
		                    
		                    '<td><input type="number" class="goods_price" name="spec['+sku_arr+'][goods_price]" lay-verify="required" placeholder="请输入商品单价" class="layui-input"/></td>'+
		                    '<input type="hidden" class="spec_sku_id" value="'+sku_arr+'" name="spec['+sku_arr+'][spec_sku_id]"/>'+

		                    '<td><input type="number" class="inventory" name="spec['+sku_arr+'][inventory]" lay-verify="required" placeholder="请输入商品库存" class="layui-input"/></td>'+
		                    '<td> <button type="button" onclick="del_td(this)" class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon" ></i> 删除</button></td>'
		                    '</tr>';
		                    $('#my-table').show();
		                    $("#tbody").append(html);
		                }
		            }else {
		                $('#my-table').hide();
		            }
		        }
		
		        // generateGroup([["红色","蓝色"],["X","XL"],["10m","20m"],["8g","16g"]]);
		
		    </script>
			
	</body>

