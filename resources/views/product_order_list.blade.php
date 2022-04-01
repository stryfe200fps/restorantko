<div class="row">
<div class="col-md-8">
<div class="card"> 
<h3 style="margin-top:20px; margin-left:20px"> order list</h3>
<table class="productCart" >
<tr>
    <th>name</th>
    <th>price</th>
    <th>quantity</th>
</tr>

@foreach ( App\Models\OrderRow::where('order_id', $widget['orderId'])->get() as $orderRow )
    <tr> 
        <td> {{ $orderRow->name }} </td>
        <td>  ₱{{ number_format( $orderRow->price ) }}</td>
        <td> {{ $orderRow->quantity}}</td>
    </tr>
@endforeach
    <tr>
        <td> </td>
        <td> <b>Total </b> </td>
        <td> <b> ₱{{ number_format($widget['total']) }} </b> </td>
    </tr>
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
    })
    
</script>

