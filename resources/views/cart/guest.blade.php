@extends('layouts.app')
@section('content')
<h1>我的購物車</h1>
<form action="# method="post" id="order-form">
    @csrf    
<table class="table table-striped">
    <tr>
        <th colspan="2">商品名稱</th>
        <th nowrap class="text-right">商品單價</th>
        <th nowrap class="text-center">購買數量</th>
        <th nowrap class="text-right">小計</th>
        <th>功能</th>
    </tr>
   
    @forelse ($product as $cart)
    <tr>
       <td>
           <a target="_blank" href="/product/{{$cart->id}}">
              <img src="{{$cart->image_url}}" class="img-thumbnail" style="width:120px;">
           </a>
       </td>
       <td>
           <a target="_blank" href="/product/{{$cart->id}}"><h5>{{$cart->title}}</h5></a>
           @if (!$cart->on_sale)
           <div class="warning">該商品已下架</div>
           @endif
        </td>
        <td class="text-right"><span id="price-{{$cart->id}}">{{$cart->price}}</span></td>
        <td class="text-center"><input type="number" min="1" class="form-control tex-center amount" name="amount[{{$cart->id}}]" value="{{$cart->amount}}" data-cartid="{{$cart->id}}"></td>
        <td class="text-right"><span class="sum" id="sum-{{$cart->id}}">{{$cart->price * $cart->amount}}</span></td>
        <td nowrap><a href="#" class="btn btn-danger btn-sm btn-del-from-cart" data-id="{{$cart->id}}">移除</a></td>     
    </tr>
    @empty
    <tr>
        <td><h1>目前購物車內沒有商品</h1></td>
    </tr>
     @endforelse
</table>
@if (count($product))
<div class="text-center">
  <button type="submit" class="btn btn-primary btn-add-to-cart">送出訂單</button>
</div>
    
@endif
</form>
@endsection


@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            $('.btn-del-from-cart').click(function () {
                var product_id=$(this).data('id');
                swal({
                    title:"確認要刪除商品?",
                    icon:"warning",
                    buttons:["取消","確定"],
                    dangerMode: true,
                }).then(function (willDeltete) {
                   if(!willDeltete){
                       return;
                   }
                   axios.delete('/cart/' + product_id)
                   .then(function(){
                       location.reload()
                   }); 
                });
            });
        });
        $('.amount').change(function (){
            var cartid = $(this).data('cartid');
            var sum = $(this).val() * $('#price-'+cartid).text();
            $('#sum-'+cartid).text(sum);
        });

        $('.btn-add-to-cart').click(function () {
        @guest
            swal('請先登入', '', 'error');
        @endguest 
        });

 </script>
@endsection 

