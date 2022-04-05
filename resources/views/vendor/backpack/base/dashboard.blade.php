@extends(backpack_view('blank'))

@section('content')
    <div class="row gap-3">
        <div class="col-md-2 mx-2 card">
            <h4>Today Earnings</h4>
            <p id="today-sales">num</p>
        </div>
        <div class="col-md-2 mx-2 card">
            <h4>All time sales</h4>
            <p id="total-sales">num</p>
        </div>
        <div class="col-md-2 mx-2 card">
            <h4>Total product</h4>
            <p id="total-product">num</p>
        </div>
        <div class="col-md-2 mx-2 card">
            <h4>Total User</h4>
            <p id="total-user">num</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 card">  <canvas id="myChart"  height="300"></canvas>  </div>
    </div>   

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"> </script>
<script>
$.get('/admin/dashboard/sales/all', function (data, status) {
        $('#total-sales').html(
'₱' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
);
});
$.get('/admin/dashboard/sales/today', function (data, status) {
        $('#today-sales').html(
'₱' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
);
});
$.get('/admin/dashboard/user/all', function (data, status) {
        $('#total-user').html(data);
});
$.get('/admin/dashboard/product/all', function (data, status) {
        $('#total-product').html(data);
});
$.get('/admin/dashboard/monthly', function (data, status) {
   
}).done(function (data) {
   let label = data.map(function (obj) {
        return obj.month
    })
    let value = data.map(function (obj) {
        return obj.total_sale
    })
const ctx = document.getElementById('myChart').getContext('2d');

const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: label,
        datasets: [{
            label: '# of Sales',
            data: value,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
 
})
// console.log(Object.entries(label));

</script>
@endsection