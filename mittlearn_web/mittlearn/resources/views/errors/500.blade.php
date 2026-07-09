@include('admin.layouts.head-links')
<main>
    <div class="container">

        <section class="section error-404 min-vh-75 d-flex flex-column align-items-center justify-content-center">
            <h1>500</h1>
            <h2 class="mt-2">Well, this is awkward... 🤖</h2>
            <p>Looks like our system took a little nap. Maybe it's dreaming of faster load times! 🚀</p>
            <p>Don’t worry, our tech wizards are already on it! 🧙‍♂️✨</p>
            <a href="{{ url('/') }}" class="btn">Take me home 🏠</a>
            {{-- <a class="mt-4 btn btn-danger" href="{{ url('/report-error') }}">Report Issue 🐞</a> --}}
            <img src="{{ asset('admin\img\500-error.png') }}" class="img-fluid py-5" alt="Page Not Found" width="200" height="200">
            {{-- <div class="credits">
          Developed by <a href="https://www.qdegrees.com/">QDegrees Services</a>
        </div> --}}

        </section>

    </div>
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <div class="card mt-4 p-3 text-start" style="width: 50%;">
                <h5 class="text-center mb-3">Report Issue</h5>
                @if (session('success'))
                    <div class="alert alert-success small">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('report.error') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="url" class="form-label">Page URL</label>
                        <input type="url" class="form-control" id="url" name="url"
                            value="{{ url()->current() }}" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_note" class="form-label">Additional Details (optional)</label>
                        <textarea class="form-control" id="user_note" name="user_note" rows="2"
                            placeholder="What were you doing when this happened?"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Submit</button>
                </form>
            </div>

        </section>
        {{-- ✅ Error Report Form --}}

    </div>
</main>
</main><!-- End #main -->

@include('admin.layouts.footer-links')
