@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/mosaics">Currencies Management</a>
 <a href="/mosaics/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Currency Edition Form</h1>
    <hr>
    @if ($mode == 'update')
     <form action="{{url('mosaics', [$mosaic->id])}}" method="POST">
    @else
     <form action="/mosaics" method="post">
    @endif

     {{ csrf_field() }}
     <div class="form-group">
        <label for="namespace">Namespace</label>
        <input type="text" class="form-control" id="namespace"  name="namespace" value="{{ isset($mosaic) ? $mosaic->namespace : Request::old('namespace') }}">
      </div>
      <div class="form-group">
        <label for="mosaic">Mosaic Name</label>
        <input type="text" class="form-control" id="mosaic"  name="mosaic" value="{{ isset($mosaic) ? $mosaic->mosaic_name : Request::old('mosaic') }}">
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection