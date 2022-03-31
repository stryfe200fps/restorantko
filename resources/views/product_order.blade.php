

<div class="row">
<div class="col-md-8">
<div class="card"> 
<table class="productCart" >
<tr> 
    <th>name</th>
    <th>price</th>
    <th>action</th>
</tr>

@foreach ( App\Models\Product::get() as $product )
    <tr> 
        <td> {{ $product->name }} </td>
        <td>  ₱{{ number_format( $product->price ) }}</td>
        <td> <a href="#" onclick="add({{ $product->id}} , {{$product->price}}  , {{ json_encode($product->name) }}  )" ><i class="fa fa-ban"></i> add to cart </a> </td>
    </tr>
@endforeach
</table>
</div></div>
</div>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        var table = new simpleDatatables.DataTable(".productCart", {
            searchable: false,
            paging: false,
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
        counts = {};
        dbItem = {} ;
        let count = cart.forEach( function (x) { counts[x] = (counts[x] || 0) + 1;  } )
         dbPush.forEach( function (x) { dbItem[x] = (dbItem[x] || 0) + 1;  } )
        let item = '';
        let $total = 0;
        let send = [];
        for (const [key, value] of Object.entries(counts)) {
            item += ` <tr><td> ${value} </td> <td> ${key.split(',')[2] } </td> <td>₱${key.split(',')[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')} </td> </tr>`;
            $total += value * parseFloat(key.split(',')[1]);
        }
        item += ` <tr><td></td> <td> <b>total</b> </td> <td><b>₱${$total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</b> </td> </tr>`;

       $(".item").html(item);
       $('.json-holder').val(JSON.stringify(dbPush));
    }
</script>

