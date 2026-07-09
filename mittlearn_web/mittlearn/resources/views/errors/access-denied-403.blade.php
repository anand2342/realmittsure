@include('admin.layouts.head-links')

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>403</h1>
        <h2>Access Denied! You do not have permission to view this page.</h2>
        <a class="btn" href="{{ route('/') }}">Back to home</a>
        <img src="{{ asset('admin\img\not-found.svg') }}" class="img-fluid py-5" alt="Page Not Found" width="200" height="200">
        {{-- <div class="credits">
          Developed by <a href="https://www.qdegrees.com/">QDegrees Services</a>
        </div> --}}
      </section>

    </div>
  </main><!-- End #main -->

  @include('admin.layouts.footer-links')
