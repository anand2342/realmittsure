@extends('userPortal.layouts.master')
@section('content')
    @include('admin.layouts.flash-messages')
    <div class="dashboardMain p-4">
        <div class="row px-lg-1">
            <div class="col-lg-9 px-lg-2 mb-3">
                <div class="cardBox h-100">
                    <div class="headingBx pb-3">
                        <h4>Subscription</h4>
                    </div>
                    <ul class="nav nav-tabs onlineTabs tbs widthFit">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#planDetailsTb"
                                type="button">Plan Details</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#billingInfoTb"
                                type="button">Billing Information</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transactionTb"
                                type="button">Transaction</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="planDetailsTb">
                            <h3 class="fs-8 mt-4">Plan Details</h3>
                            <div class="planInfo mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="planContent">
                                            <div class="planName">
                                                <figure><img src="{{ asset('frontend/images/mittlearnround-logo.svg') }}"
                                                        alt="" width="40">
                                                </figure>
                                                <div class="">
                                                    <h3>{!! $data['plan']->planDetails->name ??
                                                        '<strong>There is no subscription plan currently.</strong><br><span style="font-size: 0.9em; color: #777;">Please subscribe to a plan and unlock amazing content!</span>' !!}</h3>

                                                    @if (isset($data['plan']->end_date))
                                                        <p>Next billing on
                                                            {{ \Carbon\Carbon::parse($data['plan']->end_date ?? '')->format('d F Y') }}
                                                        </p>
                                                    @endif
                                                    <div class="d-md-flex align-items-center gap-4">
                                                        @if (empty($data['plan']))
                                                            <a href="{{ route('/') }}"
                                                                class="btn btn-primary-gradient rounded-1 me-2">
                                                                Subscribe Plan
                                                            </a>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-primary-gradient rounded-1 me-2"
                                                                id="generateOtpBtn" data-bs-target="#otpModal"
                                                                data-bs-toggle="modal">
                                                                Upgrade Plan
                                                            </button>
                                                        @endif
                                                        {{--  <a href=""
                                                            class="text-primary d-inline-block  py-2"><u>Cancel
                                                                Subscription</u></a>  --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="premiumTxt">
                                            <strong>Premium:
                                                {{ number_format((float) ($data['plan']->planPrice->final_price ?? '0'), 2) }}
                                                {{ $data['plan']->planDetails->currency ?? '' }} /
                                                {{ ucfirst($data['plan']->planPrice->duration_type ?? '') }}
                                            </strong>

                                            <span>Next billing amount <b> <strong>₹</strong>
                                                    {{ number_format((float) ($data['plan']->planPrice->final_price ?? '0'), 2) }}
                                                </b></span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @if (!empty($data['plan']))
                                    <h3 class="fs-8">Access Details</h3>
                                    <div class="accessDetails mt-3">
                                        <ul class="accessUl">
                                            @if (!empty($data['plan']))
                                                @foreach ($data['plan']->planFeatures as $item)
                                                    <li>
                                                        <div class="accessTxt">
                                                            <img src="{{ asset('frontend/images/Icon-check.svg') }}"
                                                                alt="" width="16">Access to
                                                            {{ $item->title }}
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="billingInfoTb">
                            <h5 class="fs-8 mt-4 mb-3 fw-semibold">Billing Information</h5>
                            <div class="table-responsive tbleDiv">
                                <table class="table table-bordered  ">

                                    <body>
                                        @if (!empty($data['plan']))
                                            <tr>
                                                <td><b>Student Full Name</b></td>
                                                <td>{{ $data['user']->name ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Email</b></td>
                                                <td>{{ $data['user']->email ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Address</b></td>
                                                <td>{{ $data['user']->studentDetails->address ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Country</b></td>
                                                <td>India</td>
                                            </tr>
                                            <tr>
                                                <td><b>State</b></td>
                                                <td>{{ $data['user']->studentDetails->studentState->name ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>City</b></td>
                                                <td>{{ $data['user']->studentDetails->studentCity->city ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>School Name</b></td>
                                                <td>{{ $data['user']->studentDetails->schoolDetails->name ?? 'N/a' }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Payment Method</b></td>
                                                <td>{{ $data['plan']->transaction->payer_payment_method ?? 'N/a' }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">You do not have any active plans at
                                                    the moment.</td>
                                            </tr>
                                        @endif
                                    </body>
                                </table>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="transactionTb">
                            <h5 class="fs-8 mt-4 mb-3 fw-semibold">Transaction List</h5>
                            <h6 class="fs-9 text-secondary fw-semibold">LATEST INVOICE</h6>
                            <div class="table-responsive tbleDiv mb-2">
                                <table class="table">
                                    <tbody>
                                        {{-- @dd($data['transactionLogsLatest']); --}}
                                        @if (isset($data['transactionLogsLatest']))
                                            <tr>

                                                @if (isset($data['transactionLogsLatest']->created_at))
                                                    <td class="fw-semibold">
                                                        {{ \Carbon\Carbon::parse($data['transactionLogsLatest']->created_at ?? '')->format('d F Y') }}
                                                    </td>
                                                @else
                                                    <td>N/a</td>
                                                @endif
                                                <td>
                                                    {{ $data['transactionLogsLatest']->txn_id ?? 'N/a' }}</td>
                                                <td class="text-primary">
                                                    {{ $data['transactionLogsLatest']->planDetails->name ?? 'N/a' }}</td>
                                                <td><b>
                                                        {{ $data['transactionLogsLatest']->total_amount ?? 'N/a' }}</b>
                                                </td>
                                                <td>
                                                    @if (isset($data['transactionLogsLatest']) && $data['transactionLogsLatest']->payment_state === 'success')
                                                        <span class="paidBadge">Paid</span>
                                                    @elseif (isset($data['transactionLogsLatest']) && $data['transactionLogsLatest']->payment_state === 'failed')
                                                        <span class="failedBadge">Failed</span>
                                                    @else
                                                        <span class="failedBadge">N/a</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No transactions found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <h6 class="fs-9 fs-9 text-secondary fw-semibold">PREVIOUS INVOICE</h6>
                            <div class="table-responsive tbleDiv">
                                <table class="table">
                                    <tbody>
                                        @if (isset($data['transactionLogs']) && count($data['transactionLogs']) > 0)
                                            @foreach ($data['transactionLogs'] as $item)
                                                <tr>
                                                    <td> {{ \Carbon\Carbon::parse($item->created_at ?? '')->format('d F Y') }}
                                                    </td>
                                                    <td> {{ $item->txn_id ?? 'N/a' }}</td>
                                                    <td> {{ $item->planDetails->name ?? 'N/a' }}</td>
                                                    <td><b class="text-primary">{{ $item->total_amount ?? 'N/a' }}</b>
                                                    </td>
                                                    <td>
                                                        @if ($item->payment_state === 'success')
                                                            <span class="paidBadge">Paid</span>
                                                        @else
                                                            <span class="failedBadge">Failed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No transactions found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 px-lg-2 mb-3">
                <div class="cardBox countBx p-2 h-100">
                    <div class="planName d-block">
                        <figure><img src="{{ asset('frontend/images/mittlearnround-logo.svg') }}" alt=""
                                width="36"><a href="" class="btnRecommended fs-9">Recommended</a>
                        </figure>
                        <div class="">
                            <h3>{{ $data['recomendedPlan']->name ?? '' }} <span
                                    class="text-start mb-3">{{ $data['recomendedPlan']->description ?? '' }}</span>
                            </h3>
                            <p class="fw-semibold mb-2">
                                {{ $data['recomendedPlan']->subscriptionPlanFeature[1]->title ?? '' }}</p>
                            <p class="fw-semibold"> {{ $data['recomendedPlan']->subscriptionPlanFeature[2]->title ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-center my-4">
                        <button type="button" class="btn btn-primary-gradient rounded-1 w-75" id="recGenerateOtpBtn"
                            data-bs-target="#otpModal" data-bs-toggle="modal">Choose Standard</button>
                    </div>
                    <hr>
                    <ul class="accessUl px-3">
                        @if (!empty($data['recomendedPlan']))
                            @foreach ($data['recomendedPlan']->subscriptionPlanFeature as $item)
                                <li>
                                    <div class="accessTxt">
                                        <img src="{{ asset('frontend/images/Icon-check.svg') }}" alt=""
                                            width="16">{{ $item->title }}
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-center" id="otpModal">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content border-0 rounded-1">
                <div class="modal-body p-0">
                    <div class="loginMain p-0">
                        <div class="loginSec">
                            <div class="loginFormBox  p-3">
                                <div class="modal-header border-0 py-0 pe-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="pb-5 text-center">
                                    <img src="{{ asset('frontend/images/mittlearn-logo.svg') }}" alt=""
                                        width="140">
                                </div>
                                <h3 class="text-center">OTP Verification</h3>
                                <p class=" mb-4">Enter OTP which is sent to your mobile number <br> or Email Id</p>

                                <form action="{{ route('up.upgrade.subscription') }}" method="POST">
                                    @csrf
                                    <div class="otpMain">
                                        <strong>Enter OTP</strong>
                                        <div class="otpFind">
                                            <input class="form-control otp-input" type="text" maxlength="1">
                                            <input class="form-control otp-input" type="text" maxlength="1">
                                            <input class="form-control otp-input" type="text" maxlength="1">
                                            <input class="form-control otp-input" type="text" maxlength="1">
                                            <input class="form-control otp-input" type="text" maxlength="1">
                                        </div>
                                    </div>
                                    <span class="timing mt-4">Resend OTP in <b id="countdown">30</b> seconds</span>
                                    <strong class="signupTxt pb-0">Didn't get a code? <a href="#"
                                            id="resendOtp"><u>Click to Resend</u></a></strong>

                                    <div class="text-center my-1 mt-4">
                                        <button type="button" id="verifyOtpBtn"
                                            class="btn btn-primary-gradient fs-7 rounded-2 w-75">
                                            Submit
                                        </button>
                                    </div>

                                    {{--  <strong class="signupTxt pt-1 pb-3"> <a href="#"><u> Change Email or Mobile
                                                Number</u></a></strong>  --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendOtpLink = document.getElementById('resendOtp');
            const countdownElement = document.getElementById('countdown');
            let countdownTime = 30; // Initial countdown time
            let countdownTimer; // Holds countdown timer instance

            // Function to send OTP request (Handles both first-time and resending)
            function sendOtpRequest(isResend = false) {
                let url = isResend ? "{{ route('up.resend.otp') }}" : "{{ route('up.upgarde-plan.otp') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.message) {
                            if (response.otp) {
                                let otpString = response.otp.toString().split(
                                    ""); // Convert OTP to array
                                let otpInputs = $(".otp-input");
                                if (otpInputs.length === otpString.length) {
                                    otpInputs.each(function(index) {
                                        $(this).val(otpString[index]); // Populate OTP boxes
                                    });

                                } else {
                                    console.error(
                                        "Mismatch: OTP length and input fields do not match!");
                                }

                                alert("OTP sent successfully!\nYour OTP: " + response
                                    .otp); // Show OTP in alert
                            } else {
                                alert(response.message ||
                                    "OTP sent successfully!"); // Show generic success message
                            }

                            if (isResend) {
                                resetCountdown(); // Restart countdown when resending OTP
                            }
                        } else {
                            alert(response.message || "Something went wrong. Please try again.");
                        }
                    },
                    error: function(xhr) {
                        let errorMessage =
                            "Something went wrong. Please try again."; // Default error message
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON
                                .error; // Extract error message from response
                        }
                        alert(errorMessage); // Show error in alert
                    }

                });
            }

            // Click events for first-time OTP request
            $("#generateOtpBtn, #recGenerateOtpBtn").click(function() {
                sendOtpRequest(false); // First-time OTP request
            });

            // Click event for resending OTP
            resendOtpLink.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link action
                sendOtpRequest(true); // Resend OTP
            });

            // Function to start countdown
            function startCountdown() {
                countdownTimer = setInterval(function() {
                    countdownElement.textContent = countdownTime;

                    if (countdownTime <= 0) {
                        clearInterval(countdownTimer); // Stop countdown when time is up
                        resendOtpLink.style.pointerEvents = "auto"; // Re-enable link
                        resendOtpLink.style.color = "#30C768"; // Change color to active state
                    } else {
                        countdownTime--;
                    }
                }, 1000);
            }

            // Function to reset countdown timer
            function resetCountdown() {
                clearInterval(countdownTimer); // Clear existing countdown
                countdownTime = 30; // Reset countdown to 30 seconds
                countdownElement.textContent = countdownTime; // Update display
                resendOtpLink.style.pointerEvents = "none"; // Disable resend link
                resendOtpLink.style.color = "grey"; // Change color when disabled
                startCountdown(); // Start countdown again
            }

            // Initially disable resend link and start countdown
            resendOtpLink.style.pointerEvents = "none";
            resendOtpLink.style.color = "grey";
            startCountdown();
        });
    </script>

    <script>
        $(document).ready(function() {
            let selectedPlanId = null; // Variable to store the selected plan ID
            const session_id = 'dsfdfjfkjsdnf'; // Assuming `globalVar.sessionId` is set from the backend
            // Event listener for "Choose Standard" button
            $("#recGenerateOtpBtn").click(function() {
                selectedPlanId =
                    "{{ session('recomendedPlanId') }}"; // Set to recomendedPlanId from session

                // Dynamically update the URL for "Choose Standard" button
                const updatedUrl =
                    `{{ route('plan.detail', '') }}/${selectedPlanId}?session_id=${session_id}`;
                $(this).attr('href', updatedUrl); // Update href attribute
            });

            // Event listener for "Upgrade Plan" button
            $("#generateOtpBtn").click(function() {
                selectedPlanId = "{{ session('plan_id') }}"; // Set to upgradedPlan from session

                // Dynamically update the URL for "Upgrade Plan" button
                const updatedUrl =
                    `{{ route('plan.detail', '') }}/${selectedPlanId}?session_id=${session_id}`;
                $(this).attr('href', updatedUrl); // Update href attribute
            });

            // OTP Verification on Submit
            $("#verifyOtpBtn").click(function() {
                let otpEntered = "";
                $(".otp-input").each(function() {
                    otpEntered += $(this).val(); // Concatenate all OTP input values
                });

                if (!selectedPlanId) {
                    alert('No plan selected. Please choose a plan first.');
                    return;
                }

                $.ajax({
                    url: "{{ route('up.upgrade.subscription.otp.check') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        otp: otpEntered,
                        plan_id: selectedPlanId // Send selected plan ID
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); // OTP verified successfully
                            window.location.href = `{{ route('plan.detail', ':planId') }}`
                                .replace(':planId', response.planId)
                                .concat(
                                    `?session_id=${session_id}`
                                ); // Append session_id to the URL
                        } else {
                            $(".otp-error").remove(); // Remove old errors
                            $(".otpMain").after(
                                '<p class="otp-error text-danger text-center">' + response
                                .error + '</p>'
                            );
                        }
                    },
                    error: function(xhr) {
                        $(".otp-error").remove(); // Remove old errors
                        $(".otpMain").after(
                            '<p class="otp-error text-danger text-center">Invalid OTP. Please try again.</p>'
                        );
                    }
                });
            });
        });
    </script>




@endsection
