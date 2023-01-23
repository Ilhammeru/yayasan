@extends('layouts.base')

@push('styles')
    <style>
        .empty-data {
            width: 100px;
            height: auto;
        }
    </style>
@endpush

@section('content')
    <div class="block full">
        <div class="text-center">
            <img src="{{ asset('assets/img/empty_data.jpg') }}" class="empty-data" alt="">
            <p>@lang('view.empty_income_categories')</p>
        </div>
    </div>
@endsection