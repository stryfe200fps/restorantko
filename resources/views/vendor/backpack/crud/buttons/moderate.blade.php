

@if ($crud->hasAccess('update'))
<a href="{{ url($crud->route.'/'.$entry->getKey().'/moderate') }} " ><i class="fa fa-ban"></i> upload </a>
@endif