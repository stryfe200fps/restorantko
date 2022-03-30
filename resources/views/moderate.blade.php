@extends(backpack_view('blank'))


@section('content')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

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

