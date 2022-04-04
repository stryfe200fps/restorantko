

<div class="row">
<div class="col-md-8">
<div class="card"> 
<table class="productCart" >
<tr> 
    <th>name</th>
    <th>price</th>
    <th>quantity</th>
    <th>action</th>
</tr>

@foreach ( App\Models\Product::get() as $product )
    <tr> 
        <td> {{ $product->name }} </td>
        <td>  ₱{{ number_format( $product->price ) }}</td>
        <td> <input type="number" value="1" style="width:55px;" min="1" onkeypress="ignoreMe(event)" oncopy="return false" onpaste="return false" id="n{{$product->id}}">  </td>
        <td> <button onclick="add({{ $product->id}} , {{$product->price}}  , {{ json_encode($product->name) }}  )" ><i class="fa fa-ban"></i> add to cart </button> </td>
    </tr>
@endforeach
</table>
</div></div>
</div>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    function ignoreMe(e)
    {
    if (e.which != 8 && e.which != 0 && e.which < 48 || e.which > 57)
    {
        e.preventDefault();
    }

    if ( $(`#${e.srcElement.id}`).val().length > 2 ) {

        e.preventDefault();
    }
    
    }

    $(document).ready(function () {
        var table = new simpleDatatables.DataTable(".productCart", {
            searchable: false,
        });
        var table = new simpleDatatables.DataTable(".cart", {
            searchable: false,
            paging: false,
        });
      
    })
    var cart = [];
    var dbPush = [];
    function add(id, price, name)
    {
        cart.push([id, price, name]);
        dbPush.push(id);
        counts = {};
        dbItem = {} ;
        let count = cart.forEach( function (x) { counts[x] = parseInt($('#n'+ x).val());  } )
         dbPush.forEach( function (x) { dbItem[x] = parseInt($('#n'+ x).val());  } )
        let item = '';
        let $total = 0;
        for (const [key, value] of Object.entries(counts)) {
            item += ` <tr><td> ${value} </td> <td> ${key.split(',')[2] } </td> <td>₱${key.split(',')[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')} </td> </tr>`;
            $total += value * parseFloat(key.split(',')[1]);
        }
        item += ` <tr><td></td> <td> <b>total</b> </td> <td><b>₱${$total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</b> </td> </tr>`;
        
       $(".item").html(item);
       $(".total").val($total);
       $('.json-holder').val(JSON.stringify(dbItem));
       return false;
    }
</script>

