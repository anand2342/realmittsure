<img src="data:image/png;base64, {{ $qrCodeBase64 }}" alt="QR Code with Logo">

@extends('layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    @livewire('test-multiselect')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
@endsection
