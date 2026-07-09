<div class="row g-3">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div wire:ignore.self>
        @foreach ($courseSets as $index => $set)
            <div class="row mb-2" wire:key="course-set-{{ $index }}">
                {!! Form::hidden("course_sets[$index][id]", $set['id'] ?? null) !!}

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label("course_sets[{$index}][series_id]", 'Series', ['class' => 'form-label required']) !!}

                    <select class="form-control" name="course_sets[{{ $index }}][series_id]"
                        wire:model="courseSets.{{ $index }}.series_id" x-data="{ index: {{ $index }} }"
                        x-on:change="$wire.getSeriesId($event.target.value, index)">
                        <option value="">Select Series</option>
                        @foreach ($series as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label("course_sets[$index][classes_ids]", 'Class', ['class' => 'form-label required']) !!}
                    <select name="course_sets[{{ $index }}][classes_ids][]" class="js-select2 form-select"
                        multiple x-data x-init="initSelect2($el, @this.entangle('courseSets.{{ $index }}.classes_ids'))">
                        @foreach ($classOptions[$index] ?? [] as $id => $name)
                            <option value="{{ $id }}"
                                {{ in_array($id, $set['classes_ids'] ?? []) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($index > 0)
                    <div class="col-md-12 mt-2 text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-course-btn"
                            data-index="{{ $index }}" wire:click="removeCourseSet({{ $index }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="text-end">
            <button type="button" class="btn btn-sm btn-primary " wire:click="addCourseSet">Add More</button>
        </div>
    </div>


    <script>
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='courseSets.'][wire\\:model$='.series_id']")) {
                setTimeout(initSelect2, 500); // Short delay to allow DOM updates
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest(".remove-course-btn")) {
                setTimeout(() => {
                    initSelect2(); // optional reinitialization
                }, 500);
            }
        });
    </script>



</div>
