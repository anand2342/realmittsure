<div>
    <h4>Plan Packs</h4>
    <hr class="form-divider">

    <!-- Radio Buttons to Select All Course or Pack of Courses -->
    <div class="mb-3">
        <label>
            <input type="radio" name="pack_type" wire:click="setPackType('pack_of_courses')" value="{{ $packType }}"
                {{ $packType == 'pack_of_courses' ? 'checked' : '' }} /> Pack of Courses
        </label>
        <label class="ms-3">
            <input type="radio" name="pack_type" wire:click="setPackType('all_courses')" value=" {{ $packType }}"
                {{ $packType == 'all_courses' ? 'checked' : '' }} /> All Courses
        </label>
    </div>

    <table class="table table-bordered">
        <tr>
            <th class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">Set Of Courses</th>
            <th>Discount</th>
            <th class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">Free Academic Course</th>
            <th class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">Free Talent & Skills Course</th>
            <th>Actions</th>
        </tr>

        @foreach ($packRows as $index => $row)
            <tr>
                <td class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">
                    {{ Form::hidden("pack_rows[{$index}][id]", $row['id']) }}
                    {{ Form::hidden("pack_rows[{$index}][plan_id]", $row['plan_id']) }}
                    {{ Form::number("pack_rows[{$index}][set_of_courses]", $row['set_of_courses'], [
                        'class' => 'form-control',
                        'placeholder' => 'Set Of Courses',
                        'disabled' => $packType == 'all_courses' ? true : false,
                    ]) }}
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-4">
                            {{ Form::select(
                                "pack_rows[{$index}][discount_type]",
                                config('constants.DISCOUNT_TYPES'),
                                $row['discount_type'],
                                [
                                    'class' => 'form-select',
                                    'placeholder' => '--Select--',
                                    'id' => "discount_type_{$index}",
                                    'data-index' => $index,
                                ],
                            ) }}
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text" id="discount-symbol-{{ $index }}"></span>
                                {{ Form::number("pack_rows[{$index}][discount_value]", $row['discount_value'], [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Discount Value',
                                ]) }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">
                    {{ Form::number("pack_rows[{$index}][free_course_academic]", $row['free_course_academic'], [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Number Of Free Academic Course',
                        'disabled' => $packType == 'all_courses' ? true : false,
                    ]) }}
                </td>

                <td class="{{ $packType == 'all_courses' ? 'd-none' : '' }}">
                    {{ Form::number("pack_rows[{$index}][free_course_non_academic]", $row['free_course_non_academic'], [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Number Of Free Talent & Skills Course',
                        'disabled' => $packType == 'all_courses' ? true : false,
                    ]) }}
                </td>

                <td>
                    <button wire:click="removeRow({{ $index }})" type="button" class="btn btn-danger btn-sm"
                        title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="6" class="text-right">
                <button wire:click="addRow()" type="button" class="btn btn-success btn-sm" title="Add Row"
                    {{ $isDisableAddMoreBtn ? 'disabled' : '' }}>
                    Add More
                </button>
            </td>
        </tr>
    </table>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update the discount symbol based on discount type
            function updateDiscountSymbol(index) {
                const discountType = document.getElementById(`discount_type_${index}`);
                const discountSymbol = document.getElementById(`discount-symbol-${index}`);
                if (discountType && discountSymbol) {
                    discountSymbol.textContent = discountType.value === 'flat' ? '₹' : '%';
                }
            }

            // Add event listeners for all discount type selects
            document.querySelectorAll('[id^="discount_type_"]').forEach(function(select) {
                const index = select.getAttribute('data-index');
                // Update symbol initially for each row
                updateDiscountSymbol(index);
                // Add change event listener to update on change
                select.addEventListener('change', function() {
                    updateDiscountSymbol(index);
                });
            });
        });
    </script>
@endpush
