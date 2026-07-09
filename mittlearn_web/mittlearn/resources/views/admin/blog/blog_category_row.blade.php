<tr>
    <td>
        @if ($child_index !== '')
            {{ $parent_index . '.' . $child_index }}
        @else
            {{ $parent_index . '.' }}
        @endif
    </td>
    <td>{{ $data->name }}</td>
    <td>
        <div class="d-flex align-items-center">
            @isPermission('blog.category.edit')
                <a class="btn btn-sm btn-warning me-2" href="{{ route('blog.category.edit', $data->id) }}" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
            @endisPermission

            @if (is_null($data->parent_id))
                <button class="btn btn-sm btn-primary me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#subcategories-{{ $data->id }}" title="Sub-Category">
                    <i class="fa fa-code-fork"></i>
                    <span class="badge bg-white text-primary">
                        {{ $data->children()->count() }}
                    </span>
                </button>
                <a class="btn btn-sm btn-info me-2" href="{{ route('blog.sub_category.create', $data->id) }}">
                    <i class="bi bi-plus-lg"></i>
                </a>
            @endif
            @isPermission('blog.category.delete')
                <button class="btn btn-sm btn-danger"
                    onclick="confirmDelete('{{ route('blog.category.delete', $data->id) }}')">
                    <i class="fa fa-trash"></i>
                </button>
            @endisPermission

        </div>
    </td>
</tr>

<tr>
    <td colspan="3">
        <div class="collapse" id="subcategories-{{ $data->id }}">
            <table class="table table-bordered">
                <tbody>
                    @if ($data->children->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No subcategories available</td>
                        </tr>
                    @else
                        @foreach ($data->children as $subcategory)
                            @include('admin.blog.blog_category_row', [
                                'child_index' => '',
                                'parent_index' => $parent_index . '.' . $loop->iteration,
                                'data' => $subcategory,
                            ])
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </td>
</tr>
