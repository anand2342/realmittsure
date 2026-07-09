{{-- dropdown_item.blade.php --}}
<li class="dropdown-submenu">
    <a class="dropdown-item" href="#">{{ $item->name }} 
    <span class="float-end custom-toggle-arrow">&#187;</span>
    </a>
    @if (isset($item->children) && count($item->children) > 0)
        <ul class="dropdown-menu">
            @foreach ($item->children as $child)
                @include('admin.courses.category_dropdowns', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>

 <nav class="nav justify-content-center" aria-label="Secondary navigation">
              <li class="nav-item dropdown">
                <button type="button" class="btn btn- dropdown-toggle form-control" role="button" data-bs-toggle="dropdown"                     aria-expanded="false">
                  Multi-level Item
                </button>
                <ul class="dropdown-menu">
                    @foreach ($categories as $item)
                        @include('admin.courses.category_dropdowns', ['item' => $item])
                    @endforeach
                  <li><a class="dropdown-item" href="#"> Menu Item 1</a></li>
                  <li><a class="dropdown-item" href="#"> Menu Item 2</a></li>
                  <li class="dropdown-submenu">
                    <a class="dropdown-item" href="#"> Second Level <span
                        class="float-end custom-toggle-arrow">&#187</span></a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Second Level Item 1</a></li>
                      <li><a class="dropdown-item" href="#">Second Level Item 2</a></li>
    
                      <li class="dropdown-submenu">
                        <a class="dropdown-item" href="#"> Third Level <span
                            class="float-end custom-toggle-arrow">&#187</span></a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="#">Third Level Item 1</a></li>
                          <li><a class="dropdown-item" href="#">Third Level Item 2</a></li>
                        </ul>
                      </li>
    
    
                    </ul>
                  </li>
                </ul>
              </li>
  </nav>