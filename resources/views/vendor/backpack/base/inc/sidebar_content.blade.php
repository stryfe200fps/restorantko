<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('order') }}'><i class='nav-icon la la-cart-arrow-down'></i> Orders</a></li>
{{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('order-row') }}'><i class='nav-icon la la-cart-plus'></i> Order rows</a></li> --}}
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('product') }}'><i class='nav-icon la la-hamburger'></i> Products</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('allergen') }}'><i class='nav-icon la la-skull-crossbones'></i> Allergens</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-o'></i> <span>Pages</span></a></li>
<hr>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-user'></i> Users</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('address') }}'><i class='nav-icon la la-map-marker'></i> Addresses</a></li>
<hr>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('product-image') }}'><i class='nav-icon la la-images'></i> Product images</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>