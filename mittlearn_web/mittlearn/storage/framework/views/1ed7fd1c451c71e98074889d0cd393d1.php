<?php echo $__env->make('admin.layouts.head-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h2>The page you are looking for doesn't exist.</h2>
        <a class="btn" href="<?php echo e(route('/')); ?>">Back to home</a>
        <img src="<?php echo e(asset('admin\img\not-found.svg')); ?>" class="img-fluid py-5" alt="Page Not Found" width="200" height="200">
        
      </section>

    </div>
  </main><!-- End #main -->

  <?php echo $__env->make('admin.layouts.footer-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/errors/404.blade.php ENDPATH**/ ?>