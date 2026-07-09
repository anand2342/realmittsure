<!-- Single Dropdown Item -->
<a class="dropdown-item" id="dropdownItem_{{ $category['id'] }}" href="javascript:void(0)" data-value="{{ $category['id'] }}">
    {{ $category['name'] }}
</a>

@if (!empty($category['children']))
    <!-- Nested Dropdown -->
    <ul class="dropdown-menu dropdown-submenu">
        @foreach ($category['children'] as $childCategory)
            <li>
                @include('admin.courses.child-dropdown-item', ['category' => $childCategory])
            </li>
        @endforeach
    </ul>
@endif
