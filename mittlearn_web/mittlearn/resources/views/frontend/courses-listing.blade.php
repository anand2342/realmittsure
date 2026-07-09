@extends('frontend.layouts.master')

@section('content')
    <div>
        @if ($category)
            @switch($category->name)
                @case('1')
                    @include('frontend.academic-courses-list', ['coursesList' => $acadCoursesList])
                    @break

                @case('2')
                    @include('frontend.nonacademic-courses-list', ['coursesList' => $nonAcadCoursesList])
                    @break

                @default
                    @include('frontend.category-list-not-found')
            @endswitch
        @else
            @include('frontend.category-list-not-found')
        @endif
    </div>
@endsection


