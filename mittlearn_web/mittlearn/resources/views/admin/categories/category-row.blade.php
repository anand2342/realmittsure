<tr>
    <td>
        @if ($child_index !== '')
            {{ $parent_index . '.' . $child_index }}
        @else
            {{ $parent_index }}.
        @endif
    </td>
    <td>{{ $category->name }}</td>
    <td>
        <span class="badge {{ $category->status ? 'text-success' : 'text-danger' }}">
            {{ config('constants.STATUS_LIST')[$category->status] ?? 'Unknown Status' }}
        </span>
    </td>
    <td>
        <div class="d-flex align-items-center">
            @isPermission('sub-category.edit')
                <a class="btn btn-sm btn-warning me-2" href="{{ route('sub-category.edit', $category->id) }}" title="Edit">
                    <i class="fa fa-pencil"></i>
                </a>
            @endisPermission

            <button class="btn btn-sm btn-primary me-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#subcategories-{{ $category->id }}" title="Sub-Group">
                <i class="fa fa-code-fork"></i>
                <span class="badge bg-white text-primary">
                    {{ $category->children()->count() }}
                </span>
            </button>
            @livewire('category-index', ['category' => $category->id])

            @if ($category->parent_id == 1 && !in_array($category->id, [6, 7]))
                <a href="{{ route('sub-category.field-add', $category->id) }}" class="btn btn-sm btn-info me-2">
                    Config Form Fields
                </a>
            @elseif ($category->parent_id == 1)
                <button class="btn btn-sm btn-secondary me-2" type="button">
                    Config Form Fields
                </button>
            @endif
        </div>
    </td>
</tr>

<tr>
    <td colspan="4">
        <div class="collapse" id="subcategories-{{ $category->id }}">
            <table class="table table-bordered">
                <tbody>
                    @if ($category->children->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No subcategories available</td>
                        </tr>
                    @else
                        @foreach ($category->children as $subcategory)
                            @include('admin.categories.category-row', [
                                'child_index' => '',
                                'parent_index' => $parent_index . '.' . $loop->iteration,
                                'category' => $subcategory,
                            ])
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </td>
</tr>
@if ($category->parent_id == 1)
    <tr>
        <td colspan="4">
            <div class="collapse" id="config-form-{{ $category->id }}">
                @include('admin.categories.form-fields-config', ['category' => $category])
            </div>
        </td>
    </tr>
@endif
