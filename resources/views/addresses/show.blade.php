@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/addresses">NEM Addresses</a>
 <a href="/addresses/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Showing Address {{ $address->bip44_path }}</h1>
 
    <div class="jumbotron text-center">
        <p>
            <strong>Public Key:</strong> {{ $address->public_key }}<br>
            <strong>Address:</strong> {{ $address->address }}
        </p>
    </div>
@endsection