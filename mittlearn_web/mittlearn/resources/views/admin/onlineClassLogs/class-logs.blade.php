@extends('admin.layouts.master')

@section('content')

    @section('breadcrumb')
        <div class="pagetitle">
            <h1>Online Class Logs</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Online Class Logs</li>
                <li class="breadcrumb-item active">Logs</li>
                </ol>
            </nav>
        </div>
    @endsection

    <section class="section">
     <div class="row">
        <div class="col-lg-12">

          
                @livewire('online-class-logs',
                [
                  'classes' => $classes,
                  'schoolList' => $schoolList,
                  'schoolId' => $schoolId, 
                ])               
          
        </div>
      </div>
    </section>
@endsection





