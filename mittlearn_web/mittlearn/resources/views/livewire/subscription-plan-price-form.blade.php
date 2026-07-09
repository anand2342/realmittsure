<div>
    <table class="table table-bordered">
        <tr>
            <th>Plan Validity</th>
            <th>Selling Price</th>
            <th>Discount</th>
            <th>Final Price</th>
            {{-- <th></th> --}}
        </tr>

        @foreach ($this->priceRows as $index => $row)
            <tr>
                <td>
                    {{ Form::hidden("price_row[{$index}][id]", $row['id']) }}
                    {{ Form::hidden("price_row[{$index}][plan_id]", $row['id']) }}
                    {{ Form::select("price_row[{$index}][duration_type]", $durationTypesListUpdated, $row['duration_type'], ['class' => 'form-select', 'placeholder' => '--Select--', 'wire:change' => "onChangeFiledValue(\$event.target.value, {$index}, 'duration_type')"]) }}
                </td>
                <td>
                    {{ Form::number("price_row[{$index}][price]", $row['price'], ['class' => 'form-control', 'placeholder' => 'Enter Price', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, {$index}, 'price')"]) }}
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-4">
                            {{ Form::select("price_row[{$index}][discount_type]", config('constants.DISCOUNT_TYPES'), $row['discount_type'], ['class' => 'form-select', 'placeholder' => '--Select--', 'id' => "discount_type-price_{$index}", 'data-index-price' => $index, 'wire:change' => "onChangeFiledValue(\$event.target.value, {$index}, 'discount_type')"]) }}
                        </div>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="discount-symbol-price-{{ $index }}"></span>
                                    {{ Form::number("price_row[{$index}][discount_value]", $row['discount_value'], ['class' => 'form-control', 'placeholder' => 'Enter Amount', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, {$index}, 'discount_value')"]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    {{ Form::text("price_row[{$index}][final_price]", $row['final_price'], ['class' => 'form-control', 'placeholder' => 'Plan Final Price', 'readonly' => true, 'disabled' => true]) }}
                </td>
                {{-- <td>
              <button wire:click="removeRow({{ $index }})" type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
            </td> --}}
            </tr>
        @endforeach
        {{-- <tr>
            <td colspan="5" class="text-right">
              <button wire:click="addRow()" type="button" class="btn btn-success btn-sm" title="Delete Endorsement" {{$isDisableAddMoreBtn ? 'disabled' : ''}}>Add More</button>
            </td>
          </tr> --}}
    </table>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update the discount symbol based on discount type
            function updateDiscountSymbol(index) {
                let discountType = document.getElementById(`discount_type-price_${index}`);
                let discountSymbol = document.getElementById(`discount-symbol-price-${index}`);
                if (discountType && discountSymbol) {
                    discountSymbol.textContent = discountType.value === 'flat' ? '₹' : '%';
                }
            }

            // Add event listeners for all discount type selects
            document.querySelectorAll('[id^="discount_type-price_"]').forEach(function(select) {
                let index = select.getAttribute('data-index-price');
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
