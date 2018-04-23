@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/mosaics">Currencies Management</a>
 <a href="/mosaics/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Showing Currency {{ $mosaic->fqmn }}</h1>
 
    <div class="jumbotron text-center">
        <p>
            <strong>Namespace:</strong> {{ $mosaic->namespace }}<br>
            <strong>Mosaic:</strong> {{ $mosaic->mosaic_name }}
            <strong>mosaicId:</strong> {{ $mosaic->fqmn }}
        </p>
    </div>
@endsection