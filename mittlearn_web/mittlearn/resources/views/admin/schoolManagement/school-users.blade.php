@extends('admin.layouts.master')

@section('content')

    @section('breadcrumb')
        <div class="pagetitle">
            <h1>School Users</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item">School Users</li>
                <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>
    @endsection

    <section class="section">
     <div class="row">
        <div class="col-lg-12">

          
                @livewire('school-users',
                [
                  'classes' => $classes,
                  'schoolList' => $schoolList,
                  'schoolId' => $schoolId, 
                ])               
          
        </div>
      </div>
    </section>
@endsection





