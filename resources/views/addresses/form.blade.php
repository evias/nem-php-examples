@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/addresses">NEM Addresses</a>
 <a href="/addresses/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Address Edition Form</h1>
    <hr>
    @if ($mode == 'update')
     <form action="{{url('addresses', [$address->id])}}" method="POST">
    @else
     <form action="/addresses" method="post">
    @endif

     {{ csrf_field() }}
     <div class="form-group">
        <label for="bip44Path">BIP44 Derivation Path</label>
        <input type="text" class="form-control" id="bip44Path"  name="bip44_path" value="{{ isset($address) ? $address->bip44_path : Request::old('bip44_path') }}">
      </div>
      <div class="form-group">
        <label for="publicKey">Public Key</label>
        <input type="text" class="form-control" id="publicKey"  name="public_key" value="{{ isset($address) ? $address->public_key : Request::old('public_key') }}">
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ isset($address) ? $address->address : Request::old('address') }}">
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