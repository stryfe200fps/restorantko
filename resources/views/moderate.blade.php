@extends(backpack_view('blank'))

@php
  $breadcrumbs = [
      'Admin' => backpack_url('dashboard'),
      'Dashboard' => false,
  ];
@endphp

@section('content')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<div class="d-inline-flex align-items-center "> <h1>Upload Image</h1> <a class="ml-4" href="{{ backpack_url('product') }}"> back </a> </div>
<br>
<span style="color:red;" id="validation"></span>
<form action="/target" class="dropzone" id="my-great-dropzone">
    @csrf
</form>
<button id="upload" > upload </button>
<script>

    let product_id = {{ $product_id }}
let dropzone = Dropzone;
  dropzone.options.myGreatDropzone = { // camelized version of the `id`
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 2, // MB
    autoProcessQueue: false,
    url: 'upload',
    parallelUploads: 10,
    uploadMultiple: true,
    init: function () {
        let myDropzone = this;
        document.getElementById('upload').addEventListener("click", function (e) {
          if (! myDropzone.files.length ) 
            document.getElementById('validation').innerHTML = 'insert image'
          else 
            document.getElementById('validation').innerHTML = ''
          
            e.preventDefault();
            myDropzone.processQueue();
        }),
        this.on("sending", function (file, xhr, formData) {
            formData.append('product_id', product_id);
        });
    } 
  };

</script>
@endsection

