<header class="dashboardHead">
    <div class="leftItem">
        <a href="javascript:void(0)"><img src="<?php echo e(asset('frontend/images/mittlearn-logo.svg')); ?>" alt=""
                width="130"></a>

    </div>
    <div class="rightItem">
        <button type="button" class="toggleBtn">
            <img src="<?php echo e(asset('frontend/images/toggletop-icon.svg')); ?>" alt="" width="16" class="me-md-3">
        </button>

        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-portal-universal-search');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-42223411-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <div class="alertsSec d-lg-block d-none <?php if(Session::has('admin_id')): ?> alertWhenAdmin <?php endif; ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notificationAlerts): ?>
                <div class="alertList">
                    <a href="javascript:void(0);"><?php echo e($notificationAlerts->message); ?></a>
                    <a href="javascript:void(0);"><?php echo e($notificationAlerts->message); ?></a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <button type="button" class="searchBtn d-md-none ms-auto me-3" data-bs-toggle="dropdown"><img
                src="<?php echo e(asset('frontend/images/topsearch-icon.svg')); ?>" alt="img" width="20"></button>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Session::has('admin_id')): ?>
            <a href="<?php echo e(route('superadmin.backToAdmin')); ?>" class="btn btn-sm btn-warning ms-md-auto me-3 me-md-4">Back
                to Admin</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Session::has('parent_school_id')): ?>
            <a href="<?php echo e(route('sp.back.to.parent')); ?>" class="btn btn-sm btn-warning ms-md-auto me-3 me-md-4">Back
                to Parent School</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <a href="<?php echo e(route('sp.user.manual')); ?>" class="ms-md-auto me-3 me-md-4" data-bs-toggle="tooltip"
            data-bs-placement="top" title="User Manual / Guide">
            <i class="fa fa-info-circle fa-2x me-1"></i>
        </a>

        </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'school_teacher'): ?>
            <button class="dropdownPrf me-3" type="button" data-bs-target="#teacherProfile" data-bs-toggle="modal">
                <img src="<?php echo e(Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg')); ?>"
                    alt="profile-image"><?php echo e(ucwords(Auth::user()->name)); ?></button>
        <?php else: ?>
            <button class="dropdownPrf me-3" type="button" data-bs-target="#profile" data-bs-toggle="modal">
                <img src="<?php echo e(Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg')); ?>"
                    alt="profile-image"><?php echo e(ucwords(Auth::user()->name)); ?></button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</header>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/layouts/header.blade.php ENDPATH**/ ?>