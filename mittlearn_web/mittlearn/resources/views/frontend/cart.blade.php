@extends('frontend.layouts.master')

@section('content')
    <div class="shoppingMain">
        <div class="shoppingCart">
            <div class="container">
                @include('layouts.flash-messages')
                <div class="cartHeading">
                    <a href="{{ route('/') }}" class="">
                        <img src="{{ asset('frontend/images/cart-arrow.svg') }}" alt="" width="20">
                        <h3>Digital Content Cart</h3>
                    </a>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container cartContainer">
            @if (!empty($plan_packs['free_academic_courses']) || !empty($plan_packs['free_nonacademic_courses']))
                <div style="background: #FFD28D;"
                    class="px-md-4 freeCoursesBx d-md-flex align-items-center justify-content-between gap-2 mb-3">
                    <p class="w-100 mb-0 text-primary">
                        You are eligible for
                        @if (!empty($plan_packs['free_academic_courses']))
                            <b>{{ $plan_packs['free_academic_courses'] }} free</b> academic
                            course{{ $plan_packs['free_academic_courses'] > 1 ? 's' : '' }}
                        @endif
                        @if (!empty($plan_packs['free_academic_courses']) && !empty($plan_packs['free_nonacademic_courses']))
                            and
                        @endif
                        @if (!empty($plan_packs['free_nonacademic_courses']))
                            <b>{{ $plan_packs['free_nonacademic_courses'] }} free</b> talent & skills
                            course{{ $plan_packs['free_nonacademic_courses'] > 1 ? 's' : '' }}
                        @endif
                        ; add it for free to your cart.
                    </p>
                    <div class="text-center">
                        <a class="accessFreeCoursesBtn text-nowrap" data-plan-id="{{ base64_encode($plan->id) }}"
                            data-type="free-courses">
                            <button type="submit" class="btn btn-primary py-2">Claim Free
                                Courses</button>
                        </a>
                    </div>
                </div>
            @endif
            @if(isset($itemsCount) && $itemsCount > 0)

                <div class="row">
                    <div class="col-xl-8 col-lg-12">
                        <div class="shoppingTable">
                            <div class="tblheadCart mb-2">
                                <h2><b>{{ $itemsCount }} items</b> in your bag</h2>
                                <span>Subtotal ({{ $itemsCount }} items):
                                    <b>₹{{ number_format($totalAmount, 2) }}</b></span>
                            </div>
                            <div class="table-responsive tbleDiv">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Actual Price</th>
                                            {{-- <th>Quantity</th> --}}
                                            <th>Discounted Price</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbl_body">
                                        @foreach ($cartItems as $item)
                                            @php
                                                $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
                                                $bnrImage = App\Models\CourseMetadataValue::where(
                                                    'course_id',
                                                    $item->getCourses->id,
                                                )
                                                    ->where('field_name', 'banner_image')
                                                    ->value('field_value');
                                                $bkCoverImage = App\Models\CourseMetadataValue::where(
                                                    'course_id',
                                                    $item->getCourses->id,
                                                )
                                                    ->where('field_name', 'book_cover_image')
                                                    ->value('field_value');
                                                $thumImage = App\Models\CourseMetadataValue::where(
                                                    'course_id',
                                                    $item->getCourses->id,
                                                )
                                                    ->where('field_name', 'thumbnail_image')
                                                    ->value('field_value');
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center myCourseLft">
                                                        <figure>
                                                            @if ($bnrImage)
                                                                <img src="{{ Storage::url($bnrImage) }}"
                                                                    alt="course-thumbnail-image">
                                                            @elseif($bkCoverImage)
                                                                <img src="{{ Storage::url($bkCoverImage) }}"
                                                                    alt="course-thumbnail-image">
                                                            @elseif($thumImage)
                                                                <img src="{{ Storage::url($thumImage) }}"
                                                                    alt="course-thumbnail-image">
                                                            @else
                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                    alt="Default Image">
                                                            @endif
                                                        </figure>
                                                        <div class="coursesName">
                                                            <h3>{{ optional($item->getCourses)->course_name ?? '' }}</h3>
                                                            <p>by Mittlearn</p>
                                                            @if (optional(optional($item->getCourses)->getCategoryCourse)->name)
                                                                <span>Category:
                                                                    {{ optional(optional($item->getCourses)->getCategoryCourse)->name }}</span>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fullPriceCart">
                                                    {{ $item->full_price == 0 ? 'FREE' : '₹' . number_format($item->full_price ?? 0, 2) }}
                                                </td>
                                                {{-- <td>{{ $item->quantity ?? 1 }}</td> --}}
                                                <td>{{ $item->price == 0 ? 'FREE' : '₹' . number_format($item->price, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-link btn-delete p-0 border-0 shadow-none"
                                                        data-cart-id="{{ $item->id }}">
                                                        <img src="{{ asset('frontend/images/trash.svg') }}"
                                                            alt="trash-icon" width="15">
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12">
                        @if (isset($plan_packs['status']) && $plan_packs['status'] === 'success')
                            <div class="discountBx">
                                @php
                                    $discountValue = $plan_packs['discount']['value'];
                                    $discountType = $plan_packs['discount']['type'];
                                    $remainingCourses = $plan_packs['remaining_courses'];
                                    $discountSymbol =
                                        $discountType === 'flat' ? '₹' : ($discountType === 'percent' ? '%' : '');
                                @endphp

                                <!-- Handle flat and percentage discount types -->
                                <span><b>
                                        @if ($discountType === 'flat')
                                            {{ $discountSymbol }}{{ $discountValue }} <!-- ₹ before the value for flat -->
                                        @elseif ($discountType === 'percent')
                                            {{ $discountValue }}{{ $discountSymbol }}
                                            <!-- % after the value for percent -->
                                        @endif
                                    </b> Discount</span>

                                <p class="w-100">
                                    @if ($remainingCourses > 0)
                                        To avail this offer, add {{ $remainingCourses }} more items.
                                    @else
                                        Woohoo! You got extra off on your order.
                                    @endif
                                </p>
                            </div>
                        @endif
                        <hr>
                        <div class="cartTotal">
                            <h3 class="ps-2 mb-2">Cart Total</h3>
                            <table class="table">
                                <tr>
                                    <td>Sub Total</td>
                                    <td>₹{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Discount
                                        {{-- <span> ({{ $plan_packs['current_discount'] ?? 0 }})</span> --}}
                                    </td>
                                    <td>
                                        @if (isset($isFreeTrial) && $isFreeTrial)
                                            ₹{{ number_format($totalAmount ?? 0, 2) }}
                                        @else
                                            @if (isset($plan_packs['current_discount_type']) && $plan_packs['current_discount_type'] === 'flat')
                                                ₹{{ number_format($plan_packs['current_discount'] ?? 0, 2) }}
                                            @elseif (isset($plan_packs['current_discount_type']) && $plan_packs['current_discount_type'] === 'percent')
                                                ₹{{ number_format((($totalAmount ?? 0) * ($plan_packs['current_discount'] ?? 0)) / 100, 2) }}
                                            @else
                                                ₹0.00
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Grand Total</td>
                                    <td class="fw-bold" id="grandTotalAmount">
                                        @php
                                            $discount = 0;
                                            if (
                                                isset($plan_packs['current_discount_type']) &&
                                                $plan_packs['current_discount_type'] === 'flat'
                                            ) {
                                                $discount = $plan_packs['current_discount'];
                                            } elseif (
                                                isset($plan_packs['current_discount_type']) &&
                                                $plan_packs['current_discount_type'] === 'percent'
                                            ) {
                                                $discount = ($totalAmount * $plan_packs['current_discount']) / 100;
                                            }
                                            $isFreeTrial = $isFreeTrial ?? false; // Default to false if not set
                                        $grandTotal = $isFreeTrial ? 0 : ($totalAmount ?? 0) - ($discount ?? 0); @endphp
                                        ₹{{ $isFreeTrial ? '0.00 ' . ' (free-trial)' : number_format($grandTotal, 2) }}

                                    </td>
                                </tr>
                            </table>

                            <form action="{{ route('cart.checkout.process') }}" method="POST">
                                @csrf
                                <!-- Pass cart data as hidden inputs -->
                                <input type="hidden" name="total_amount"
                                    value="{{ $isFreeTrial ? '0.00' : number_format($totalAmount, 2) }}">
                                <input type="hidden" name="total_discount"
                                    value="{{ $isFreeTrial ? '0.00' : number_format($discount, 2) }}">
                                <input type="hidden" name="grand_total"
                                    value="{{ $isFreeTrial ? '0.00' : number_format($grandTotal, 2) }}">

                                @foreach ($cartItems as $item)
                                    <input type="hidden" name="cart_items[]" value="{{ $item->course_id }}">
                                    <input type="hidden" name="plan_id" value="{{ $item->item_id }}">
                                @endforeach

                                @if (!Auth::check())
                                    <button type="submit" class="btn btn-primary w-100 Checkout bg-white">Checkout</button>
                                @else
                                    @if (isset($isFreeTrial) && $isFreeTrial)
                                        <input type="hidden" name="is_free_trial" value="1">
                                        <button type="submit"
                                            class="btn btn-primary w-100 Checkout bg-white">Checkout</button>
                                    @else
                                        @if ($isFreeTrial)
                                            <form method="POST" action="{{ route('cart.checkout.process') }}">
                                                @csrf
                                                <input type="hidden" name="is_free_trial" value="1">
                                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                <!-- Include cart items -->
                                                <button type="submit" class="btn btn-primary w-100 Checkout bg-white">
                                                    Checkout
                                                </button>
                                            </form>
                                        @elseif($hasOnlyFreeCourses)
                                            <form method="POST" action="{{ route('cart.checkout.process') }}">
                                                @csrf
                                                <input type="hidden" name="free_checkout" value="1">
                                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                <!-- Include cart items -->
                                                <button type="submit" class="btn btn-primary w-100 Checkout bg-white">
                                                    Checkout
                                                </button>
                                            </form>
                                        @else
                                            <button id="rzp-button1" class="btn btn-primary w-100 Checkout bg-white">
                                                Checkout
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center">
                        <h4>Your cart is empty.</h4>
                        <a href="{{ route('/') }}" class="btn btn-primary">Continue Browse</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Add Free Courses route call
        $(document).ready(function() {
            const session_id = globalVar.sessionId;
            $('.accessFreeCoursesBtn').each(function() {
                const planId = $(this).data('plan-id');
                const type = $(this).data('type');
                const updatedUrl =
                    `{{ route('plan.detail', '') }}/${planId}?session_id=${session_id}&type=${type}`;
                $(this).attr('href', updatedUrl); // Update the href attribute dynamically.
            });
        });

        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            // Add to cart script
            const guestUserSessionId = globalVar.sessionId;

            const user_id = $("#userAuthId").val();
            const cart_id = $(this).data("cart-id");

            const post_data = {
                cart_id: cart_id,
                user_id: user_id,
                session_id: guestUserSessionId,
                status: "cancelled",
            };

            const endPointUrl = "delete-item-from-cart";

            globalFunc.ajaxCall(
                endPointUrl,
                post_data,
                "POST",
                globalFunc.before,
                globalFunc.handleRemoveCartSuccess,
                globalFunc.error,
                globalFunc.complete
            );
        });
        globalFunc.handleRemoveCartSuccess = function(data) {
            if (data.status === "success") {
                const {
                    cartItems,
                    itemsCount,
                    totalAmount,
                    plan_packs
                } = data.data;
                console.log(data.data);
                const cartContainer = $(".cartContainer"); // The container holding the cart items
                const emptyCartMessage = `<div class="row emptyCartMessage">
                            <div class="col-12 text-center">
                                <h4>Your cart is empty.</h4>
                                <a href="{{ route('/') }}" class="btn btn-primary">Continue Browse</a>
                            </div>
                         </div>`;

                if (cartItems.length === 0) {
                    // If the cart is empty, show the empty cart message
                    cartContainer.html(emptyCartMessage);
                    return; // Exit the function as no further updates are needed
                } else {
                    // If the cart is not empty, update the content                    
                    // Update item count and subtotal
                    $(".tblheadCart h2").html(`<b>${itemsCount} items</b> in your bag`);
                    $(".tblheadCart span").html(`Subtotal (${itemsCount} items): <b>₹${totalAmount.toFixed(2)}</b>`);

                    // Clear and update cart table
                    const cartTableBody = $(".tbl_body").empty();
                    cartItems.forEach((item) => {
                        const price = parseFloat(item.price) || 0;
                        const subtotal = price * item.quantity;

                        const image = item.get_courses.bnr_image || item.get_courses.bk_cover_image || item
                            .get_courses.thumbnail_image ||
                            '/frontend/images/default-image.jpg';
                        // const image = course.bnr_image || course.bk_cover_image || course.thumbnail_image ||
                        //     '/frontend/images/default-image.jpg';

                        cartTableBody.append(`
                        <tr>
                            <td>
                                <div class="d-flex align-items-center myCourseLft">
                                    <figure>
                                        <img src="${image}" alt="course-thumbnail-image">
                                    </figure>
                                    <div class="coursesName">
                                        <h3>${item.get_courses.course_name}</h3>
                                        <p>by Mittlearn</p>
                                        <span>Category: ${item.get_courses.get_category_course.name}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="fullPriceCart">${item.full_price === 0 ? 'FREE' : `₹${item.full_price}`}</td>
                            <td>${subtotal === 0 ? 'FREE' : `₹${subtotal.toFixed(2)}`}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-link btn-delete p-0 border-0 shadow-none" data-cart-id="${item.id}">
                                    <img src="/frontend/images/trash.svg" alt="trash-icon" width="15">
                                </button>
                            </td>
                        </tr>
                    `);
                    });

                    // Update Cart Total Section
                    if (plan_packs?.status === "success") {
                        $(".discountBx").show();
                        // Determine discount symbol placement
                        const discountSymbol = plan_packs.discount.type === "flat" ? "₹" : "%";
                        const discountValue = plan_packs.discount.value;
                        const discountText = plan_packs.discount.type === "flat" ?
                            `<b>${discountSymbol}${discountValue}</b>` // ₹ before the value
                            :
                            `<b>${discountValue}${discountSymbol}</b>`; // % after the value
                        // Update the discount message
                        $(".discountBx span").html(`${discountText} Discount`);
                        // Update the message based on remaining courses
                        $(".discountBx p").html(
                            plan_packs.remaining_courses > 0 ?
                            `To avail this offer, add ${plan_packs.remaining_courses} more items.` :
                            "Woohoo! You got extra off on your order."
                        );
                    } else {
                        $(".discountBx").hide();
                    }


                    $(".cartTotal table").html(`
                        <tr>
                            <td>Sub Total</td>
                            <td>₹${totalAmount.toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>₹${
                                (plan_packs?.current_discount_type === "flat" && plan_packs?.current_discount) 
                                    ? parseFloat(plan_packs.current_discount).toFixed(2) 
                                    : (plan_packs?.current_discount_type === "percent" && plan_packs?.current_discount) 
                                        ? ((totalAmount * parseFloat(plan_packs.current_discount)) / 100).toFixed(2) 
                                        : "0.00"
                            }</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Grand Total</td>
                            <td class="fw-bold" id="grandTotalAmount">₹${
                                (totalAmount - (
                                    (plan_packs?.current_discount_type === "flat" && plan_packs?.current_discount) 
                                        ? parseFloat(plan_packs.current_discount) 
                                        : (plan_packs?.current_discount_type === "percent" && plan_packs?.current_discount) 
                                            ? (totalAmount * parseFloat(plan_packs.current_discount)) / 100 
                                            : 0
                                )).toFixed(2)
                            }</td>
                        </tr>
                    `);


                    toastr.success("Item successfully removed from cart!", "Success");
                }

            } else {
                toastr.error("Failed to update cart. Please try again.", "Error");
            }
        };
    </script>
    <script>
        document.getElementById('rzp-button1').onclick = function(e) {
            e.preventDefault();

            // Fetch the value from the grandTotalAmount `td` element
            let grandTotalElement = document.getElementById('grandTotalAmount');
            let grandTotalText = grandTotalElement.textContent || grandTotalElement.innerText; // Get the text content
            let grandTotal = parseFloat(grandTotalText.replace(/[^0-9.-]+/g, '')); // Extract numeric value
            let cartPlanId = {{ $cartItems[0]->item_id ?? 'null' }};

            // Update Razorpay options dynamically
            var options = {
                "key": "{{ config('services.razorpay.key_id') }}", // Razorpay Key ID
                "amount": grandTotal * 100, // Amount in paise
                "currency": "INR",
                "name": "Mittlearn",
                "description": "Purchase Subscription",
                "image": "https://mittlearn.com/images/mittlearn-favicon.png",
                "handler": function(response) {
                    // Pass payment details to your server
                    $.ajax({
                        url: "{{ route('cart.checkout.process') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            razorpay_payment_id: response.razorpay_payment_id,
                            total_amount: grandTotal,
                            plan_id: cartPlanId,
                            cart_items: {!! json_encode(($cartItems ?? collect([]))->pluck('course_id')) !!},

                        },
                        success: function(data) {
                            // Redirect to home page after successful payment
                            window.location.href = "{{ route('up.dashboard') }}";
                        },
                        error: function(error) {
                            alert('Payment failed, please try again.');
                            // console.log(error);
                        }
                    });
                },
                "prefill": {
                    "name": "{{ Auth::user()->name ?? '' }}",
                    "email": "{{ Auth::user()->email ?? '' }}",
                    "contact": "{{ Auth::user()->mobile_no ?? '' }}"
                },
                "theme": {
                    "color": "#00BE55"
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        };
    </script>
@endsection
