@extends('layouts.app')

@section('content')
<div class="container">
    @can('kurator')
        @include('kurator')
    @elsecan('client')
        @include('client')
    @endcan
</div>
@endsection
