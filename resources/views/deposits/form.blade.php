@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/deposits">User Deposits</a>
 <a href="/deposits/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Deposit Edition Form</h1>
    <hr>
    @if ($mode == 'update')
     <form action="{{url('deposits', [$deposit->id])}}" method="POST">
    @else
     <form action="/deposits" method="post">
    @endif

     {{ csrf_field() }}
     <div class="form-group">
        <label for="emailAddress">User Email Address</label>
        <input type="text" class="form-control" id="emailAddress"  name="email" value="{{ isset($deposit) ? $deposit->user->email : Request::old('email') }}">
      </div>
      <div class="form-group">
        <label for="reference">Transaction Message (Invoice Reference)</label>
        <input type="text" class="form-control" id="reference"  name="reference" value="{{ isset($deposit) ? $deposit->reference : Request::old('reference') }}">
      </div>
      <div class="form-group">
        <label for="mosaic_fqmn">NEM Mosaic</label>
        <input type="text" class="form-control" id="mosaic_fqmn" name="mosaic_fqmn" value="{{ isset($deposit) ? $deposit->mosaic_fqmn : Request::old('mosaic_fqmn') }}">
      </div>
      <div class="form-group">
      <label for="sender">Recipient Address (Current app address: {{$currentAppAddress}})</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ isset($deposit) ? $deposit->address->address : Request::old('address') }}">
      </div>
      <div class="form-group">
        <label for="awaited_amount">Amount (smallest possible unit. Ex. : 1 XEM = 1000000 nem:xem)</label>
        <input type="text" class="form-control" id="awaited_amount" name="awaited_amount" value="{{ isset($deposit) ? $deposit->awaited_amount : Request::old('awaited_amount') }}">
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