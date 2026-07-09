@extends('frontend.layouts.master')

@section('content')
    <div>
       
        <div class="courseBanner">
            <div class="container">
                <div class="bannerTxt position-relative">
                    <div class="scrollDownJson">
                    </div>
                    <h1>Category List Not Found !</h1>

                    <img class="rounded-circle" src="{{ asset('frontend/images/category-not-found.jpg') }}" width="200px" alt="Banner Image">
                    <br><br>
                    
                    <a href="{{'/'}}" class="btn btn-primary-gradient rounded-1 fs-7 px-4">Go Back</a>
                </div>

            </div>
        </div>




        
        

       
       
        

      
        
    </div>
    


   <!-- V Added For Copy URL Start --------------->
    
     <!-- V Added For Copy URL End --------------->


  @endsection