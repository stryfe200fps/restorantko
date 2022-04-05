

<div class="row">
<div class="col-md-12">
<div class="card"> 
<table class="productCart" >
<thead>
<tr> 
    <th>name</th>
    <th>price</th>
    <th>quantity</th>
    <th>action</th>
</tr>
</thead>
<tbody id="orderList">
@foreach ( App\Models\Product::get() as $product )
    <tr > 
        <td> {{ $product->name }} </td>
        <td>  ₱{{ number_format( $product->price ) }}</td>
        <td> <input type="number" value="1" style="width:55px;" min="1" onkeypress="ignoreMe(event)" oncopy="return false" onpaste="return false" id="n{{$product->id}}">  </td>
        <td> 
            <button class="btn btn-success" onclick="add({{ $product->id}} , {{$product->price}}  , {{ json_encode($product->name) }}, $('#n{{$product->id}}').val()  )" ><i class="fa fa-ban"></i> order </button> 
            <button onclick="reset({{ $product->id }})" class="btn btn-error">reset</button>
        </td>

        {{-- <td> <button class="btn btn-success" name="wow1" > <i class="fa fa-ban"></i> order </button> </td> --}}
    </tr>
@endforeach
</tbody>
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
    var tableCart = new simpleDatatables.DataTable(".productCart", {
        perPage: 2        
    });
    var table = new simpleDatatables.DataTable(".cart", {
        searchable: false,
        paging: false,
    });

        
    })

    var cart = [];
   
    function add(id, price, name, quantity)
    {
        let counts = {}
        cart.push([id, price, name, quantity]);
        cart.forEach( function (x) { counts[x] = x.quantity  } )

        let item = '';
        let $total = 0;
        for (const [key, value] of Object.entries(counts)) {
            item += ` <tr><td> ${key.split(',')[3]} </td> <td> ${key.split(',')[2] } </td> <td>₱${key.split(',')[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')} </td> </tr>`;
            $total += key.split(',')[3] * parseFloat(key.split(',')[1]);
        }
        item += ` <tr><td></td> <td> <b>total</b> </td> <td><b>₱${$total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</b> </td> </tr>`;
        
       $(".item").html(item);
       $(".total").val($total);
       $('.json-holder').val(JSON.stringify(cart));
       console.log('----cart------')
       console.log(cart);
    }

    function reset(id) {
 let counts = {}
        cart = cart.filter(function (item) {
            return item[0] != id;
        })
        cart.forEach( function (x) { counts[x] = x.quantity  } )
       console.log('----remove------')
        console.log(cart);
        let item = '';
        let $total = 0;
        for (const [key, value] of Object.entries(counts)) {
            item += ` <tr><td> ${key.split(',')[3]} </td> <td> ${key.split(',')[2] } </td> <td>₱${key.split(',')[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')} </td> </tr>`;
            $total += key.split(',')[3] * parseFloat(key.split(',')[1]);
        }
        item += ` <tr><td></td> <td> <b>total</b> </td> <td><b>₱${$total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</b> </td> </tr>`;
        
       $(".item").html(item);
       $(".total").val($total);
       $('.json-holder').val(JSON.stringify(cart));
    }

</script>
