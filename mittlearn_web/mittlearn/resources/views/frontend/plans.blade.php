<div class="planSection py-5">
    <div class="container">
        <div class="section-heading">
            <h2><span class="greenBorder"></span>
                Choose the plan that's right for your child</h2>
            <p>15-days free trial, cancel any time. No payment required</p>
        </div>
        <div class="row">
            @php
                $backgroundColors = ['#FFEDB6', '#D1E8FF', '#B7FFD7'];
            @endphp
            @foreach ($plans as $index => $plan)
                {{-- @php
                    $durationData = findInArray(
                        config('constants.DURATION_TYPES'),
                        'value',
                        $plan->subscriptionPlanPrice[0]->duration_type,
                    );
                @endphp --}}
                <div class="col-md-4 px-lg-4 mb-3 ">
                    <div class="standardPlan h-100"
                        style="background-color: {{ $backgroundColors[$index % count($backgroundColors)] }}">
                        <!-- <div class="standardPlan"
                        style="background-color: {{ $backgroundColors[$index % count($backgroundColors)] }};"> -->
                        @if ($plan->is_recommended == '1')
                            <b class="recommended">Recommended</b>
                        @endif
                        {{-- <strong>₹ {{ $plan->subscriptionPlanPrice[0]->final_price ?? 'N/A' }}<b>/{{$durationData['label'] ?? 'N/A'}}</b></strong> --}}
                        <span>{{ $plan->name ?? 'N/A' }} <b>{{ $plan->description ?? 'N/A' }}</b></span>
                        <hr class="hrClr">
                        <ul class="planlistUl">
                            @foreach ($plan->subscriptionPlanFeature as $plan_feature)
                                <li>
                                    <p>
                                        <img src="{{ asset('frontend/images/checkGreen.svg') }}" alt=""
                                            width="16" class="me-2">
                                        {{ $plan_feature->title }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('plan.detail', base64_encode($plan->id)) }}"
                            class="hovertextMain explore-link" data-plan-id="{{ base64_encode($plan->id) }}">
                            <p class="hovertext1">Explore</p>
                            <p class="hovertext2">Explore <i class="bi bi-arrow-right"></i></p>
                        </a>

                        <script>
                            $(document).ready(function() {
                                const session_id = globalVar.sessionId; // Assuming globalVar.sessionId holds the session ID.

                                // Update all links with the session ID
                                $('.explore-link').each(function() {
                                    const planId = $(this).data('plan-id'); // Get the base64-encoded plan ID.
                                    const updatedUrl =
                                        `{{ route('plan.detail', '') }}/${planId}?session_id=${session_id}`;
                                    $(this).attr('href', updatedUrl); // Update the href attribute dynamically.
                                });
                            });
                        </script>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
