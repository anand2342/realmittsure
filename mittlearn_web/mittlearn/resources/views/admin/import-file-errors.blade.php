@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Import Errors</h1>
        
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">Errors</div>
                            </div>
                           
                        </div>

                        <hr class="formdivider">
                        <table class="table table-striped table-bordered">
                            
                            <tbody>
                                @if (!empty($errorMsg) && count($errorMsg) > 0)
                                <ul class="list-group">
                                    @foreach ($errorMsg as $error)
                                        <li class="list-group-item list-group-item-danger"><strong>{{ $error }}</strong></li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-success">
                                    No errors to display.
                                </div>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
