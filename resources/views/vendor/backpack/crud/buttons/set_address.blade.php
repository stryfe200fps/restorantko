@if ($crud->hasAccess('update'))
<a href="{{ backpack_url('address/'. $entry->getKey() ) }} " ><i class="fa fa-ban"></i> set primary </a>
@endif