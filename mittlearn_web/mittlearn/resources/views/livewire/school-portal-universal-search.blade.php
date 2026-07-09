<div>
    <div class="searchBox dropdown-menu d-md-block">
        <input type="text" class="form-control" placeholder="Search..." wire:model.live="search">

        @if (!empty($results))
            <div class="search-results bg-white shadow mt-2 rounded position-absolute">
                @foreach ($results as $category => $items)
                    @if ($items->count() > 0)
                        <div class="search-category p-2 border-bottom">
                            <strong>{{ ucfirst(str_replace('_', ' ', $category)) }}</strong>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($items as $item)
                                <a href="{{ $this->getRoute($category, $item) }}" class="text-dark">
                                    <li class="list-group-item">
                                        {{ $item->course_name ?? ($item->title ?? $item->name) }}
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
