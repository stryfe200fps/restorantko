
@if ($widget['status'] === 'new')
<button class="btn-submit btn-success btn" onclick="setStatus('accepted')" >accept</button>
<button class="btn-submit btn-error btn" onclick="setStatus('rejected')">reject</button>
@elseif ($widget['status'] !== 'rejected' && $widget['status'] !== 'delivered')
<button class="btn-submit btn-warning btn" onclick="setStatus('preparing')">preparing</button>
<button class="btn-submit btn-warning btn" onclick="setStatus('prepared')">prepared</button>
<button class="btn-submit  btn-info btn" onclick="setStatus('delivering')">delivering</button>
<button class="btn-submit btn-info btn" onclick="setStatus('delivered')">delivered</button>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
@endif
<script>
var status = 'new';
    function setStatus(status) {
       this.status = status;
    }

  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-submit").click(function(e){
           var id = {!! json_encode($widget['orderId']) !!};

        e.preventDefault();
    var form = new FormData(); 
    form.append('id', id );
    form.append('status', status );
                $.ajax({
           type:'POST',
           processData: false,
            contentType: false,
           url:"{{ route('status') }}",
           data: form ,
           success:function(data){
               new Noty({
                type : 'success',
                text : `Order is ${status} successfully`
               }).show();
               setTimeout(function(){window.location="/admin/order"} , 1000);   
           }
        });
  
    });
    
</script>