@extends('admin.layouts.master')
<style>
    .insertButton {
        margin-left: 78%;
    }
    .checkbox-scroll {
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 4px;
    }
</style>

@section('content')
@php 
    $isEditMode = isset($coupon) && !empty($coupon);
    $heading = $isEditMode ? 'Update' : 'Add';
@endphp

<div id="page-header" class="page-header">
    <section class="section">
        <div class="pagetitle">
            <h1>Coupons</h1>
            <nav style="--bs-breadcrumb-divider: '>'; ">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::model($coupon ?? null, [
                            'url' => $coupon ? route('coupon.update', $coupon->id) : route('coupon.store'),
                            'method' => $coupon ? 'PUT' : 'POST',
                            'id' => $coupon ? 'edit-coupon-form' : 'add-coupon-form',
                            'class' => 'row g-3'
                        ]) !!}


                        <h4 class="card-title">{{ $heading }} Coupons</h4>

                        <!-- <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('code', 'Coupon Code', ['class' => 'form-label']) !!}
                            {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Code']) !!}
                        </div> -->

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('code', 'Coupon Code', ['class' => 'form-label required']) !!}
                            {!! Form::text('code', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Coupon Code',
                                'oninput' => "this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                            ]) !!}
                        </div>

                       <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('discount_type', 'Discount Type', ['class' => 'form-label required']) !!}
                            {!! Form::select('discount_type', 
                                ['flat' => 'Flat', 'percent' => 'Percentage'], 
                                old('discount_type', $coupon->discount_type ?? 'flat'), 
                                ['class' => 'form-select', 'id' => 'discount_type']) 
                            !!}
                        </div>

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('discount_value', 'Discount Value', ['class' => 'form-label required']) !!}
                            <div class="input-group">
                                <span class="input-group-text" id="discount-symbol"></span>
                                {!! Form::number('discount_value', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => 'Enter Discount Value', 'id' => 'discount_value']) !!}
                            </div>
                        </div>


                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('min_cart_value', 'Minimum Cart Value', ['class' => 'form-label']) !!}
                            {!! Form::number('min_cart_value', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => 'Enter Minimum Cart Value', 'id' => 'min_cart_value']) !!}
                        </div>

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('max_cart_value', 'Maximum Cart Value', ['class' => 'form-label']) !!}
                            {!! Form::number('max_cart_value', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => 'Enter Maximum Cart Value', 'id' => 'max_cart_value']) !!}
                            <span id="cart-value-error" style="display: none; font-size: 13px; color:red;">Maximum Cart Value should not be less than Minimum Cart Value.</span>
                        </div>

                    
                        <div class="col-md-6 col-sm-3 col-xs-12" id="upto_discount_field">
                            {!! Form::label('upto_discount', 'Upto Discount', ['class' => 'form-label']) !!}
                            {!! Form::number('upto_discount', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => 'Enter Upto Discount']) !!}
                        </div>

                        

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('applicable_for', 'Applicable For', ['class' => 'form-label']) !!}
                            {!! Form::select('applicable_for', $applicableForOptions, old('applicable_for', $coupon->applicable_for ?? ''), ['class' => 'form-select', 'id' => 'applicable_for']) !!}
                        </div>

                        <!-- Category Dropdown with Search and Select All -->
                        <div class="col-md-6 col-sm-3 col-xs-12" id="categoryDropdown" style="{{ (old('applicable_for', $coupon->applicable_for ?? '') == 'category') ? 'display:block;' : 'display:none;' }}">
                            {!! Form::label('applicable_for_ids[]', 'Select Categories', ['class' => 'form-label']) !!}
                            <input type="text" id="categorySearch" placeholder="Search categories" class="form-control mb-2">
                            <div class="checkbox-scroll category-container">
                                <input type="checkbox" id="select_all_categories"><span class="fw-bold"> Select All</span>
                                @foreach($categories as $id => $name)
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="applicable_for_ids[]" value="{{ $id }}" class="category-checkbox"
                                        {{ in_array($id, old('applicable_for_ids', explode(',', $coupon->applicable_for_ids ?? ''))) ? 'checked' : '' }}>
                                        {{ $name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- User Dropdown with Search and Select All -->
                        <div class="col-md-6 col-sm-3 col-xs-12" id="userDropdown" style="{{ isset($coupon) && $coupon->applicable_for == 'existing_users' ? 'display:block;' : 'display:none;' }}">
                            {!! Form::label('applicable_for_ids[]', 'Select Users', ['class' => 'form-label']) !!}
                            <input type="text" id="userSearch" placeholder="Search users" class="form-control mb-2">
                            <div class="checkbox-scroll user-container">
                                <input type="checkbox" id="select_all_users"><span class="fw-bold"> Select All</span>
                                @foreach($users as $id => $name)
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="applicable_for_ids[]" value="{{ $id }}" class="user-checkbox"
                                        {{ in_array($id, old('applicable_for_ids', explode(',', $coupon->applicable_for_ids ?? ''))) ? 'checked' : '' }}>
                                        {{ $name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Course Dropdown with Search and Select All -->
                        <div class="col-md-6 col-sm-3 col-xs-12" id="courseDropdown" style="{{ isset($coupon) && $coupon->applicable_for == 'courses' ? 'display:block;' : 'display:none;' }}">
                            {!! Form::label('applicable_for_ids[]', 'Select Courses', ['class' => 'form-label']) !!}
                            <input type="text" id="courseSearch" placeholder="Search courses" class="form-control mb-2">
                            <div class="checkbox-scroll course-container">
                                <input type="checkbox" id="select_all_courses"><span class="fw-bold"> Select All</span>
                                @foreach($courses as $id => $name)
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="applicable_for_ids[]" value="{{ $id }}" class="course-checkbox"
                                        {{ in_array($id, old('applicable_for_ids', explode(',', $coupon->applicable_for_ids ?? ''))) ? 'checked' : '' }}>
                                        {{ $name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>



                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('usage_limit', 'Usage Limit', ['class' => 'form-label']) !!}
                            {!! Form::number('usage_limit', null, ['class' => 'form-control', 'placeholder' => 'Enter Usage Limit']) !!}
                        </div>

                       
                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('per_user_limit', 'Per User Limit', ['class' => 'form-label']) !!}
                            {!! Form::number('per_user_limit', $coupon->per_user_limit ?? 1, ['class' => 'form-control', 'placeholder' => 'Enter Per User Limit']) !!}
                        </div>


                        <!-- <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('uses_frequency', 'Uses Frequency', ['class' => 'form-label']) !!}
                            {!! Form::select('uses_frequency', ['once' => 'Once', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly'], null, ['class' => 'form-select', 'placeholder' => 'Select Uses Frequency']) !!}
                        </div> -->

                        @php
                            $todayDate = \Carbon\Carbon::today()->format('Y-m-d');
                        @endphp

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('start_date', 'Start Date', ['class' => 'form-label']) !!}
                            {!! Form::date('start_date', $coupon->start_date ?? null, ['class' => 'form-control', 'id' => 'start_date', 'placeholder' => 'Select Start Date', 'min' => $todayDate]) !!}
                        </div>

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('end_date', 'End Date', ['class' => 'form-label']) !!}
                            {!! Form::date('end_date', $coupon->end_date ?? null, ['class' => 'form-control', 'id' => 'end_date', 'placeholder' => 'Select End Date', 'min' => $todayDate]) !!}
                            <small id="dateError" style="color:red; display:none;">End date must be greater than start date.</small>
                        </div>


                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('is_active', 'Is Active', ['class' => 'form-label']) !!}
                            {!! Form::select('is_active', config('constants.STATUS_LIST'), $coupon->is_active ?? null, ['class' => 'form-select']) !!}
                        </div>

                        <div class="col-md-6 col-sm-3 col-xs-12">
                            {!! Form::label('is_clubable', 'Is Clubable', ['class' => 'form-label']) !!}
                            {!! Form::select('is_clubable', config('constants.IS_CLUBABLE'), $coupon->is_clubable ?? null, ['class' => 'form-select']) !!}
                        </div>


                        <div class="text-end">
                            {!! Form::submit($isEditMode ? 'Update' : 'Submit', ['class' => 'btn btn-primary', 'id' => 'submitButton']) !!}
                            {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!------- V Use Applicable_for click show applicable_for_ids Start --------------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>




<!-- updated but not value display edit mode -->
<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('#categorySelect, #userSelect, #courseSelect').select2({
            allowClear: true,
            width: '100%',
            placeholder: 'Search here'
        });

            $('#applicable_for').on('change', function() {
                $('#categoryDropdown, #userDropdown, #courseDropdown').hide(); 
                $('#categorySelect, #userSelect, #courseSelect').val([]).trigger('change'); 

                if ($(this).val() === 'category') {
                    $('#categoryDropdown').show();
                } else if ($(this).val() === 'existing_users') {
                    $('#userDropdown').show();
                } else if ($(this).val() === 'courses') {
                    $('#courseDropdown').show();
                }
            });

        
            if ($('#applicable_for').val()) {
                $('#applicable_for').trigger('change'); 
            }

            
            @if(isset($coupon) && $coupon->applicable_for) 
                var applicableFor = "{{ old('applicable_for', $coupon->applicable_for) }}";
                $('#applicable_for').val(applicableFor).trigger('change'); 

               
                @php
                    $oldApplicableForIds = old('applicable_for_ids', explode(',', $coupon->applicable_for_ids ?? ''));
                @endphp

                if (applicableFor === 'category') {
                    var oldCategoryIds = @json($oldApplicableForIds);
                    $('#categorySelect').val(oldCategoryIds).trigger('change');
                } else if (applicableFor === 'existing_users') {
                    var oldUserIds = @json($oldApplicableForIds);
                    $('#userSelect').val(oldUserIds).trigger('change');
                } else if (applicableFor === 'courses') {
                    var oldCourseIds = @json($oldApplicableForIds);
                    $('#courseSelect').val(oldCourseIds).trigger('change');
                }
            @endif
        });
    </script> -->


    <!--V New Onchange with Select All without Seaches-->
    <script type="text/javascript">
    $(document).ready(function() {
        $('#applicable_for').on('change', function() {
            $('#categoryDropdown, #userDropdown, #courseDropdown').hide();
            if ($(this).val() === 'category') {
                $('#categoryDropdown').show();
            } else if ($(this).val() === 'existing_users') {
                $('#userDropdown').show();
            } else if ($(this).val() === 'courses') {
                $('#courseDropdown').show();
            }
        }).trigger('change');

        // Function to filter checkboxes based on search input
        function filterCheckboxes(searchInput, containerClass) {
            const filterText = $(searchInput).val().toLowerCase();
            $(containerClass + ' .checkbox-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(filterText));
            });
        }

        // Attach keyup events for search functionality
        $('#categorySearch').on('keyup', function() {
            filterCheckboxes(this, '.category-container');
        });
        $('#userSearch').on('keyup', function() {
            filterCheckboxes(this, '.user-container');
        });
        $('#courseSearch').on('keyup', function() {
            filterCheckboxes(this, '.course-container');
        });

        // Select all for categories
        $('#select_all_categories').on('change', function() {
            $('.category-checkbox').prop('checked', this.checked);
        });
        $('.category-checkbox').on('change', function() {
            $('#select_all_categories').prop('checked', $('.category-checkbox:checked').length === $('.category-checkbox').length);
        });

        // Select all for users
        $('#select_all_users').on('change', function() {
            $('.user-checkbox').prop('checked', this.checked);
        });
        $('.user-checkbox').on('change', function() {
            $('#select_all_users').prop('checked', $('.user-checkbox:checked').length === $('.user-checkbox').length);
        });

        // Select all for courses
        $('#select_all_courses').on('change', function() {
            $('.course-checkbox').prop('checked', this.checked);
        });
        $('.course-checkbox').on('change', function() {
            $('#select_all_courses').prop('checked', $('.course-checkbox:checked').length === $('.course-checkbox').length);
        });
    });
</script>

<!--V Applicable for on change value show Start --------------------------------->
    <!--V old way working fine -->
    <!-- <script type="text/javascript">
        $(document).ready(function() {
            $('#categorySelect, #userSelect, #courseSelect').select2({
                allowClear: true,
                width: '100%',
                placeholder: 'Search here'
            });

            $('#applicable_for').on('change', function() {
                $('#categoryDropdown, #userDropdown, #courseDropdown').hide(); 
                $('#categorySelect, #userSelect, #courseSelect').val([]).trigger('change'); 

                if ($(this).val() === 'category') {
                    $('#categoryDropdown').show();
                } else if ($(this).val() === 'existing_users') {
                    $('#userDropdown').show();
                } else if ($(this).val() === 'courses') {
                    $('#courseDropdown').show();
                }
            });

        
            if ($('#applicable_for').val()) {
                $('#applicable_for').trigger('change'); 
            }

            
            @if(isset($coupon) && $coupon->applicable_for) 
                var applicableFor = "{{ old('applicable_for', $coupon->applicable_for) }}";
                $('#applicable_for').val(applicableFor).trigger('change'); 

               
                @php
                    $oldApplicableForIds = old('applicable_for_ids', explode(',', $coupon->applicable_for_ids ?? ''));
                @endphp

                if (applicableFor === 'category') {
                    var oldCategoryIds = @json($oldApplicableForIds);
                    $('#categorySelect').val(oldCategoryIds).trigger('change');
                } else if (applicableFor === 'existing_users') {
                    var oldUserIds = @json($oldApplicableForIds);
                    $('#userSelect').val(oldUserIds).trigger('change');
                } else if (applicableFor === 'courses') {
                    var oldCourseIds = @json($oldApplicableForIds);
                    $('#courseSelect').val(oldCourseIds).trigger('change');
                }
            @endif
        });
    </script> -->


    <!--V New Onchange with Select All without Seaches-->
    <!-- <script type="text/javascript">
        $(document).ready(function() {
            $('#applicable_for').on('change', function() {
                $('#categoryDropdown, #userDropdown, #courseDropdown').hide();
                if ($(this).val() === 'category') {
                    $('#categoryDropdown').show();
                } else if ($(this).val() === 'existing_users') {
                    $('#userDropdown').show();
                } else if ($(this).val() === 'courses') {
                    $('#courseDropdown').show();
                }
            }).trigger('change'); 


            // Select all for categories
            $('#select_all_categories').on('change', function() {
                $('.category-checkbox').prop('checked', this.checked);
            });
            $('.category-checkbox').on('change', function() {
                $('#select_all_categories').prop('checked', $('.category-checkbox:checked').length === $('.category-checkbox').length);
            });

            // Select all for users
            $('#select_all_users').on('change', function() {
                $('.user-checkbox').prop('checked', this.checked);
            });
            $('.user-checkbox').on('change', function() {
                $('#select_all_users').prop('checked', $('.user-checkbox:checked').length === $('.user-checkbox').length);
            });

            // Select all for courses
            $('#select_all_courses').on('change', function() {
                $('.course-checkbox').prop('checked', this.checked);
            });
            $('.course-checkbox').on('change', function() {
                $('#select_all_courses').prop('checked', $('.course-checkbox:checked').length === $('.course-checkbox').length);
            });
        });
    </script> -->

   <script type="text/javascript">
    $(document).ready(function() {
        $('#applicable_for').on('change', function() {
            $('#categoryDropdown, #userDropdown, #courseDropdown').hide();
            if ($(this).val() === 'category') {
                $('#categoryDropdown').show();
            } else if ($(this).val() === 'existing_users') {
                $('#userDropdown').show();
            } else if ($(this).val() === 'courses') {
                $('#courseDropdown').show();
            }
        }).trigger('change');

        // Function to filter checkboxes based on search input
        function filterCheckboxes(searchInput, containerClass) {
            const filterText = $(searchInput).val().toLowerCase();
            $(containerClass + ' .checkbox-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(filterText));
            });
        }

        // Attach keyup events for search functionality
        $('#categorySearch').on('keyup', function() {
            filterCheckboxes(this, '.category-container');
        });
        $('#userSearch').on('keyup', function() {
            filterCheckboxes(this, '.user-container');
        });
        $('#courseSearch').on('keyup', function() {
            filterCheckboxes(this, '.course-container');
        });

        // Select all for categories
        $('#select_all_categories').on('change', function() {
            $('.category-checkbox').prop('checked', this.checked);
        });
        $('.category-checkbox').on('change', function() {
            $('#select_all_categories').prop('checked', $('.category-checkbox:checked').length === $('.category-checkbox').length);
        });

        // Select all for users
        $('#select_all_users').on('change', function() {
            $('.user-checkbox').prop('checked', this.checked);
        });
        $('.user-checkbox').on('change', function() {
            $('#select_all_users').prop('checked', $('.user-checkbox:checked').length === $('.user-checkbox').length);
        });

        // Select all for courses
        $('#select_all_courses').on('change', function() {
            $('.course-checkbox').prop('checked', this.checked);
        });
        $('.course-checkbox').on('change', function() {
            $('#select_all_courses').prop('checked', $('.course-checkbox:checked').length === $('.course-checkbox').length);
        });
    });
</script>

<!--V Applicable for on change value show End-- --------------------------------->






<!--V Discount Type Symbol display in discount_value Field Start ---------------->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type');
        const discountSymbol = document.getElementById('discount-symbol');

        function updateDiscountSymbol() {
            discountSymbol.textContent = discountType.value === 'flat' ? '₹' : '%';
        }

        updateDiscountSymbol();
        discountType.addEventListener('change', updateDiscountSymbol);
    });
</script>
<!--V Discount Type Symbol display in discount_value Field End------------->

<!--V Minimum Cart Value-Maximum Cart Value Start ------------------------->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minCartValue = document.getElementById('min_cart_value');
        const maxCartValue = document.getElementById('max_cart_value');
        const errorSpan = document.getElementById('cart-value-error');

        function validateCartValues() {
            const minValue = parseFloat(minCartValue.value) || 0;
            const maxValue = parseFloat(maxCartValue.value) || 0;

            if (maxValue > 0 && maxValue < minValue) {
                errorSpan.style.display = 'block';
                maxCartValue.setCustomValidity("Maximum Cart Value must be greater than or equal to Minimum Cart Value.");
            } else {
                errorSpan.style.display = 'none';
                maxCartValue.setCustomValidity("");
            }
        }

        minCartValue.addEventListener('input', validateCartValues);
        maxCartValue.addEventListener('input', validateCartValues);
    });
</script>
<!--V Minimum Cart Value-Maximum Cart Value End ------------------------------>

<!--V Upto Discount Should be hide if select Discount Type Flat START -------->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type');
        const uptoDiscountField = document.getElementById('upto_discount_field');

        function toggleUptoDiscountField() {
            if (discountType.value === 'flat') {
                uptoDiscountField.style.display = 'none';
            } else {
                uptoDiscountField.style.display = 'block';
            }
        }

        toggleUptoDiscountField();
        discountType.addEventListener('change', toggleUptoDiscountField);
    });
</script>
<!--V Upto Discount Should be hide if select Discount Type Flat End -------->

<!--V End Date Always should be grater than Start Date ------------ -------->
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const dateError = document.getElementById('dateError');

        function validateDates() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && endDate <= startDate) {
                dateError.style.display = 'block'; 
                endDateInput.setCustomValidity("End date must be greater than start date.");
            } else {
                dateError.style.display = 'none';
                endDateInput.setCustomValidity("");
            }
        }

        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    });
</script>
<!--V End Date Always should be grater than Start Date ------------ -------->























@endsection
