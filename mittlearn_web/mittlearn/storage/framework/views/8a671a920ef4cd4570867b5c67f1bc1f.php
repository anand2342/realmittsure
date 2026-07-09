<?php $__env->startSection('content'); ?>
    <style>
        .big-toggle-switch {
            position: relative;
            display: inline-block;
            width: 70px;
            height: 35px;
        }

        .big-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .big-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 35px;
        }

        .big-toggle-slider::before {
            position: absolute;
            content: "OFF";
            height: 29px;
            width: 29px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            color: #000;
            font-size: 11px;
            line-height: 29px;
            text-align: center;
            transition: 0.4s;
            border-radius: 50%;
            font-weight: bold;
        }

        .big-toggle-switch input:checked+.big-toggle-slider {
            background-color: #b0efb2;
        }

        .big-toggle-switch input:checked+.big-toggle-slider::before {
            transform: translateX(35px);
            content: "ON";
            background-color: #30C768;
            color: #fff;
        }
    </style>
    <div>
        <div class="pagetitle">
            <h1>Settings</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs tbs " id="settingsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                        aria-selected="true">General</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="emailSetting-tab" data-bs-toggle="tab"
                                        data-bs-target="#emailSetting" type="button" role="tab"
                                        aria-controls="emailSetting" aria-selected="false">Email</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="smsSetting-tab" data-bs-toggle="tab"
                                        data-bs-target="#smsSetting" type="button" role="tab"
                                        aria-controls="smsSetting" aria-selected="false">SMS</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="series-tab" data-bs-toggle="tab"
                                        data-bs-target="#series" type="button" role="tab" aria-controls="series"
                                        aria-selected="false">Website Course Series</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="Footer-tab" data-bs-toggle="tab"
                                        data-bs-target="#Footer" type="button" role="tab" aria-controls="Footer"
                                        aria-selected="false">User Portal: Footer</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="app-tab" data-bs-toggle="tab"
                                        data-bs-target="#app" type="button" role="tab" aria-controls="app"
                                        aria-selected="false">User Portal: App Links</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="google-login-tab" data-bs-toggle="tab"
                                        data-bs-target="#loginAccessSeting" type="button" role="tab"
                                        aria-controls="loginAccessSeting" aria-selected="false">Login
                                        Access</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-btn" id="ERPDataSync-tab" data-bs-toggle="tab"
                                        data-bs-target="#ERPDataSync" type="button" role="tab"
                                        aria-controls="ERPDataSync" aria-selected="false">ERP Data Sync</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="<?php echo e(route('tickets.create')); ?>"><button class="nav-link tab-btn" >Ticket / Task</button></a>
                                </li>

                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content" id="settingsTabsContent">
                                <!-- General Tab -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                    aria-labelledby="general-tab">

                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <!-- General Settings -->
                                    <br />
                                    <h3 class="setting-card-title ms-2">Site Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('site_page_title', 'Site Page Title', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('site_page_title', $settings['site_page_title'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Site Page Information'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('site_logo', 'Site Logo', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::file('site_logo', ['class' => 'form-control'])); ?>

                                        <img src="<?php echo e(Storage::url('uploads/logo/' . $settings['site_logo'])); ?>"
                                            alt="Profile Image" width="200" height="100">
                                    </div>
                                    <br />
                                    <!-- Contact Info -->
                                    <h3 class="setting-card-title ms-2">Contact Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <?php echo Form::label('email', 'Email', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::email('email', $settings['email'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Email'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('contact_number', 'Contact Number', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('contact_number', $settings['contact_number'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Contact Number'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('address', 'Address', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('address', $settings['address'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Address'])); ?>

                                    </div>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Social Media Links</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <?php echo Form::label('facebook', 'Facebook', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('facebook', $settings['facebook'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Facebook Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('instagram', 'Instagram', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('instagram', $settings['instagram'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Instagram Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('twitter', 'Twitter', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('twitter', $settings['twitter'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Twitter Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('linkedin', 'LinkedIn', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('linkedin', $settings['linkedin'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter LinkedIn Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('you_tube', 'YouTube', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('you_tube', $settings['you_tube'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter YouTube Link'])); ?>

                                    </div>
                                     <br />
                                    <h3 class="setting-card-title ms-2">RM Support Contact Number (Used in SMS Notifications)</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::label('rm_support_mobile_number', 'RM Support Contact Number', [
                                            'class' => 'form-label',
                                        ]); ?>

                                        <?php echo e(Form::text('rm_support_mobile_number', $settings['rm_support_mobile_number'] ?? '', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter support mobile number (e.g., 8696259964)',
                                        ])); ?>

                                        <small class="text-muted">
                                            This number will be used in system-generated SMS messages sent to RMs for
                                            sharing school contact details.
                                        </small>
                                    </div>


                                    <div class="mt-4">
                                        <?php echo Form::submit('Save General Setting', ['class' => 'btn btn-primary']); ?>

                                        
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>

                                <!-- Email Tab -->
                                <div class="tab-pane fade" id="emailSetting" role="tabpanel"
                                    aria-labelledby="emailSetting-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Email Settings</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_mailer', 'Mail Mailer', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('mail_mailer', $settings['mail_mailer'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter mailer ex. smtp'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_host', 'Mail Host', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('mail_host', $settings['mail_host'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter mail host ex. gmail.com'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_port', 'Mail Port', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('mail_port', $settings['mail_port'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter mail port ex. 487'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_user_name', 'Mail Username', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('mail_user_name', $settings['mail_user_name'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter mail username'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_password', 'Mail Password', ['class' => 'form-label']); ?>

                                        <div class="password-toggle position-relative">
                                            <?php echo e(Form::input('password', 'mail_password', $settings['mail_password'] ?? '', [
                                                'class' => 'form-control pe-5',
                                                'placeholder' => 'Enter SMS API key',
                                                'id' => 'mail_password',
                                            ])); ?>

                                            <i class="fa fa-eye-slash password-toggle-icon"
                                                onclick="togglePassword('mail_password')"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('mail_encryption', 'Mail Encryption', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('mail_encryption', $settings['mail_encryption'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Encryption ex.ssl,tls'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('from_mail_address', 'From Mail Address', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('from_mail_address', $settings['from_mail_address'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter from mail address'])); ?>

                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">Save Email Setting</button>
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                                <!-- SMS Tab -->
                                <div class="tab-pane fade" id="smsSetting" role="tabpanel"
                                    aria-labelledby="smsSetting-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">SMS Settings</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('sms_sender_id', 'SMS Sender Id', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('sms_sender_id', $settings['sms_sender_id'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter SMS sender id'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('sms_api_key', 'SMS API Key', ['class' => 'form-label']); ?>

                                        <div class="password-toggle position-relative">
                                            <?php echo e(Form::input('password', 'sms_api_key', $settings['sms_api_key'] ?? '', [
                                                'class' => 'form-control pe-5',
                                                'placeholder' => 'Enter SMS API key',
                                                'id' => 'sms_api_key',
                                            ])); ?>


                                            <i class="fa fa-eye-slash password-toggle-icon"
                                                onclick="togglePassword('sms_api_key')"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('sms_api_url', 'SMS API Url', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('sms_api_url', $settings['sms_api_url'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter SMS api url'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('sms_api_username', 'SMS API Username', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('sms_api_username', $settings['sms_api_username'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter SMS api username'])); ?>

                                        <?php echo e(Form::hidden('sms_api_active', '1')); ?>

                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">Save SMS Setting</button>
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                                <div class="tab-pane fade" id="series" role="tabpanel" aria-labelledby="series-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'series.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Website (Front-page) Series Courses View Setting
                                    </h3>
                                    <hr class="form-divider">
                                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend-courses');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1313870023-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary">Save Website Course Series</button>
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                                <div class="tab-pane fade" id="Footer" role="tabpanel" aria-labelledby="Footer-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Company Name Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('company_name', 'Company Name', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('company_name', $settings['company_name'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Company Name'])); ?>

                                    </div>
                                    <br />
                                    <h3 class="setting-card-title ms-2">User Contact Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <?php echo Form::label('user_email', 'Email', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::email('user_email', $settings['user_email'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Email'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('user_contact_number', 'Contact Number', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_contact_number', $settings['user_contact_number'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Contact Number'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('user_address', 'Address', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_address', $settings['user_address'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Address'])); ?>

                                    </div>
                                    <br />
                                    <h3 class="setting-card-title ms-2">User Social Media Links</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <?php echo Form::label('user_facebook', 'Facebook', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_facebook', $settings['user_facebook'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Facebook Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('user_instagram', 'Instagram', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_instagram', $settings['user_instagram'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Instagram Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('user_twitter', 'Twitter', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_twitter', $settings['user_twitter'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User Twitter Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('user_linkedin', 'LinkedIn', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_linkedin', $settings['user_linkedin'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User LinkedIn Link'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('user_youtube', 'YouTube', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('user_youtube', $settings['user_youtube'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter User YouTube Link'])); ?>

                                    </div>
                                    <div class="mt-2">
                                        <?php echo Form::submit('Save User Portal: Footer', ['class' => 'btn btn-primary']); ?>

                                        
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                                <div class="tab-pane fade" id="app" role="tabpanel" aria-labelledby="app-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Play Store Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <?php echo Form::label('play_heading', 'Heading', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('play_heading', $settings['play_heading'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Heading'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('play_description', 'Description', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('play_description', $settings['play_description'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Description'])); ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('play_logo', 'Logo', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::file('play_logo', ['class' => 'form-control'])); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($settings['play_logo'])): ?>
                                            <img src="<?php echo e(Storage::url('uploads/logo/' . $settings['play_logo'])); ?>"
                                                alt="App Logo" width="200" height="100">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('play_image', 'Image', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::file('play_image', ['class' => 'form-control'])); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($settings['play_image'])): ?>
                                            <img src="<?php echo e(Storage::url('uploads/logo/' . $settings['play_image'])); ?>"
                                                alt="Profile Image" width="200" height="100">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12  mb-3">
                                        <?php echo Form::label('play_link', 'Link', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('play_link', $settings['play_link'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Link'])); ?>

                                    </div>
                                    <br />
                                    <h3 class="setting-card-title ms-2">App Store Information</h3>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('app_heading', 'Heading', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('app_heading', $settings['app_heading'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Heading'])); ?>

                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('app_description', 'Description', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('app_description', $settings['app_description'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Description'])); ?>

                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('app_logo', 'Logo', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::file('app_logo', ['class' => 'form-control'])); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($settings['app_logo'])): ?>
                                            <img src="<?php echo e(Storage::url('uploads/logo/' . $settings['app_logo'])); ?>"
                                                alt="Profile Image" width="200" height="100">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('app_image', 'Image', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::file('app_image', ['class' => 'form-control'])); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($settings['app_image'])): ?>
                                            <img src="<?php echo e(Storage::url('uploads/logo/' . $settings['app_image'])); ?>"
                                                alt="Profile Image" width="200" height="100">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                                        <?php echo Form::label('app_link', 'Link', ['class' => 'form-label']); ?>

                                        <?php echo e(Form::text('app_link', $settings['app_link'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Link'])); ?>

                                    </div>

                                    <div class="mt-2">
                                        <?php echo Form::submit('Save User Portal: Download App', ['class' => 'btn btn-primary']); ?>

                                        
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>

                                <div class="tab-pane fade" id="loginAccessSeting" role="tabpanel"
                                    aria-labelledby="google-login-tab">
                                    <?php echo e(Form::model($settings, ['route' => 'setting.save', 'id' => 'google-login-settings-form', 'class' => 'row g-3', 'method' => 'POST'])); ?>

                                    <?php echo csrf_field(); ?>
                                    <br />
                                    <h3 class="setting-card-title ms-2">Login Session Access Setting</h3>
                                    <hr class="form-divider">

                                    
                                    <div class="col-md-6 mb-3">
                                        <?php echo Form::label('multiple_login_enabled', 'Enable Multiple Login (For Student Roles Only)', [
                                            'class' => 'form-label',
                                        ]); ?>

                                        
                                        <div class="form-check-toggler">
                                            <input type="hidden" name="multiple_login_enabled" value="0">

                                            <label class="big-toggle-switch">
                                                <input type="checkbox" id="multiple_login_enabled"
                                                    name="multiple_login_enabled" value="1"
                                                    <?php echo e($settings['multiple_login_enabled'] ?? false ? 'checked' : ''); ?>>
                                                <span class="big-toggle-slider"></span>
                                            </label>
                                        </div>


                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Save Login Access Settings</button>
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                                <div class="tab-pane fade" id="ERPDataSync" role="tabpanel"
                                    aria-labelledby="ERPDataSync-tab">
                                    <br />
                                    <h3 class="setting-card-title ms-2">Data Sync from ERP to Mittlearn LMS</h3>
                                    <hr class="form-divider">
                                    <div class="mt-3">
                                        <a href="<?php echo e(route('erp-data.schools.index')); ?>" class="btn btn-primary">Open ERP
                                            Data</a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const multipleLoginEnabled = document.getElementById('multiple_login_enabled');

            multipleLoginEnabled.addEventListener('change', function(e) {
                const isChecked = e.target.checked;

                Swal.fire({
                    title: 'Are you sure?',
                    text: isChecked ?
                        "You are about to ENABLE multiple logins for student roles." :
                        "You are about to DISABLE multiple logins for student roles.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        // If cancelled, revert checkbox to previous state
                        multipleLoginEnabled.checked = !isChecked;
                    }
                });
            });
        });
    </script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = field.parentElement.querySelector('.password-toggle-icon');

            if (field.type === 'password') {
                field.type = 'text'; // Show password
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye'); // Change to slashed eye
            } else {
                field.type = 'password'; // Hide password
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash'); // Change to regular eye
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/settings/add.blade.php ENDPATH**/ ?>