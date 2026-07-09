<div>
    <div class="">
        <div class="container">
            <div class="contentNeeds">
                <div class="maincon">
                    <div class="layoutText">
                        <figure>
                            <img src="{{ asset('frontend/images/academic.png') }}" alt="">
                        </figure>
                        <h3>Academic Digital Content</h3>
                        <p>simply dummy text of the printing and typesetting industry.</p>
                    </div>
                    {{-- Toggle Switch --}}
                    <div class="toggleGroup">
                        <div class="togglediv">
                            <input type="checkbox" id="switch" wire:click="toggleViewIndex"
                                @if ($view === 'index-nonacademic') checked @endif />
                            <label for="switch">Toggle</label>
                        </div>
                        <p>Switch the content as needed</p>
                    </div>

                    <div class="layoutText">
                        <figure>
                            <img src="{{ asset('frontend/images/talent.png') }}" alt="">
                        </figure>
                        <h3>Talent Digital Content</h3>
                        <p>simply dummy text of the printing and typesetting industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @if ($view === 'index-academic')
    @else
    @endif --}}
 {{-- {{$view}} --}}
    <section class="academic-content" style="display:{{ $view == 'index-academic' ? 'block' : 'none' }} ">
        @include('frontend.index-academic')
    </section>
    <section class="academic-nonacademic-content" style="display:{{ $view == 'index-academic' ? 'none' : 'block' }} ">

        @include('frontend.index-nonacademic')
    </section>

</div>
