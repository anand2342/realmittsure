@include('admin.layouts.head-links')

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h2>The page you are looking for doesn't exist.</h2>
        <a class="btn" href="{{ route('/') }}">Back to home</a>
        <img src="{{ asset('admin\img\not-found.svg') }}" class="img-fluid py-5" alt="Page Not Found" width="200" height="200">
        {{-- <div class="credits">
          Developed by <a href="https://www.qdegrees.com/">QDegrees Services</a>
        </div> --}}
      </section>

    </div>
  </main><!-- End #main -->

  @include('admin.layouts.footer-links')
