@extends('layouts.master_backend')
@section('content')
<div>
    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="{{url('public/images/profile.png')}}" alt="Profile" class="rounded-circle">
              <h2>{{$data_row->name ? $data_row->name : 'NA'}}</h2>
              <h3>{{($data_row->user_role && $data_row->user_role->role) ? $data_row->user_role->role : ''}}</h3>
              <p>{!! getStatusBtn($data_row->status, 1, ['badge_class'=>'rounded-pill']) !!}</p>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-details">Profile Details</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bank-details">Bank Details</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-details">
                 <br/>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->name ? $data_row->name : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Username</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->username ? $data_row->username : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Relation Manager</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->manager_info ? $data_row->manager_info->name.' ('.$data_row->manager_info->username.')' : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->gender ? $data_row->gender : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Mobile No.</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->mobile ? $data_row->mobile : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email Address</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->email ? $data_row->email : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->dob ? dateConvert($data_row->dob) : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Date of Joining</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->doj ? dateConvert($data_row->doj) : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->address ? $data_row->address : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->country ? $data_row->country : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">City</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->city ? $data_row->city : 'NA'}}</div>
                  </div>
                </div>

                <div class="tab-pane fade profile-overview" id="bank-details">
                 <br/>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Bank Name</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->bank_name ? $data_row->bank_name : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Bank Branch</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->bank_branch ? $data_row->bank_branch : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">IFSC Code</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->bank_ifsc_code ? $data_row->bank_ifsc_code : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Account Holder Name</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->bank_account_holder_name ? $data_row->bank_account_holder_name : 'NA'}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Account Number</div>
                    <div class="col-lg-9 col-md-8">{{$data_row->bank_account_num ? $data_row->bank_account_num : 'NA'}}</div>
                  </div>

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>
</div>       
@endsection