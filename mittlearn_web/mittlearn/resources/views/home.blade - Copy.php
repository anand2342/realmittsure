@extends('layouts.app')

@section('content')
    <div class="loginMain">
        <div class="loginSec">
            <div class="pb-3 text-center">
                <a href="{{route('/')}}"><img src="{{ asset(config('constants.SITE_LOGO')) }}" alt="" width="200"/></a>
            </div>
            <div class="loginFormBox">
                <div class="card-header">
                    <h3>Welcome to the Homepage!</h3>
                </div>

           

                    @if($isFreeTrialAvailable)
                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#basicModal" data-bs-backdrop="false">
                        Access Free Trial
                    </button>
                    @endif

                   <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Available Free Plans</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                {{ Form::open(['url' => route('purchase.subscription'), 'class' => 'row g-3', 'files' => true]) }}

                                   <div class="col-sm-6">
    <label for="academic" class="form-label">Academic</label>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Select Class
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach ($options as $key => $value)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $key }}" id="academic_{{ $key }}">
                    <label class="form-check-label" for="academic_{{ $key }}">{{ $value }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>



                                    <div class="col-sm-6">
                                        {!! Form::label('Non-Academic', 'Non-Academic', ['class' => 'form-label']) !!}
                                        <ul class="list-unstyled">
                                            @foreach ($nonAcademic as $course)
                                                <li>- {{ $course->course_name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                              

                                </div>
                                <div class="modal-footer">
                                {!! Form::submit(isset($nonAcademic) ? 'Add Plan' : 'Submit', ['class' => 'btn btn-primary']) !!}

                                </div>
                                  {!! Form::close() !!}  
                            </div>
                        </div>
                    </div>
               

               

                <div class="card-body mt-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::check())
                        <div class="alert alert-success">
                            You are logged in as <strong>{{ Auth::user()->name }}</strong>!
                        </div>
                    @else
                        <div class="alert alert-warning">
                            You are not logged in.
                        </div>
                    @endif
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                </div>
            </div>

        </div>
        <div class="mainBanner p-0">
            <span class="bgIcons1"><img src="{{ asset('frontend/images/bgIcon1.svg') }}" width="30"></span>
            <span class="bgIcons2"><img src="{{ asset('frontend/images/bgIcon2.png') }}" width="50"></span>
            <span class="bgIcons3"><img src="{{ asset('frontend/images/bgIcon3.png') }}" width="50"></span>
            <span class="bgIcons4"><img src="{{ asset('frontend/images/bgIcon4.png') }}" width="50"></span>
            <span class="bgIcons5"><img src="{{ asset('frontend/images/bgIcon5.png') }}" width="60"></span>
            <span class="bgIcons6"><img src="{{ asset('frontend/images/bgIcon6.png') }}" width="40"></span>
            <span class="bgIcons7"><img src="{{ asset('frontend/images/bgIcon7.png') }}" width="40"></span>
            <span class="bgIcons8"><img src="{{ asset('frontend/images/bgIcon8.png') }}" width="55"></span>
            <span class="bgIcons9"><img src="{{ asset('frontend/images/bgIcon9.png') }}" width="60"></span>
            <span class="bgIcons10"><img src="{{ asset('frontend/images/bgIcon10.png') }}" width="55"></span>
            <span class="bgIcons11"><img src="{{ asset('frontend/images/bgIcon11.png') }}" width="50"></span>
            <span class="bgIcons12"><img src="{{ asset('frontend/images/bgIcon12.png') }}" width="50"></span>
            <span class="bgIcons13"><img src="{{ asset('frontend/images/bgIcon13.png') }}" width="60"></span>
        </div>
    </div>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    const checkboxes = document.querySelectorAll('.form-check-input');
    const dropdownButton = document.getElementById('dropdownMenuButton');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedOptions = Array.from(checkboxes)
                .filter(i => i.checked)
                .map(i => i.nextElementSibling.textContent);

            dropdownButton.textContent = selectedOptions.length > 0 
                ? selectedOptions.join(', ') 
                : 'Select Class';
        });
    });
</script>

@endsection
