@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <upload-component></upload-component>
        </div>
    </div>

    <hr>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <download-component></download-component>
        </div>
    </div>

</div>
@endsection
