@include('admin.layouts.head-links')
<style>
    .icon-denied {
        font-size: 6rem;
        color: #ff5733;
    }
</style>
<main>
    <div class="container">
        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <i class="bi bi-x-circle icon-denied"></i>
            <h1>403</h1>
            <h2>Access Denied</h2>
            <h5>You do not have hermission to access this page.</h5>
            <a class="btn" href="{{ url()->previous() }}">Go Back</a>
            {{-- <div class="credits">
          Developed by <a href="https://www.qdegrees.com/">QDegrees Services</a>
        </div> --}}
        </section>
    </div>
</main><!-- End #main -->

@include('admin.layouts.footer-links')
