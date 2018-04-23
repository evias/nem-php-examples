@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/withdrawals">User Withdrawals</a>
 <a href="/withdrawals/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Withdrawal Edition Form</h1>
    <hr>
    @if ($mode == 'update')
     <form action="{{url('withdrawals', [$withdrawal->id])}}" method="POST">
    @else
     <form action="/withdrawals" method="post">
    @endif

     {{ csrf_field() }}
     <div class="form-group">
        <label for="emailAddress">User Email Address</label>
        <input type="text" class="form-control" id="emailAddress"  name="email" value="{{ isset($withdrawal) ? $withdrawal->user->email : Request::old('email') }}">
      </div>
      <div class="form-group">
        <label for="sender">Sender Address (Current app address: {{$currentAppAddress}})</label>
        <input type="text" class="form-control" id="sender" name="sender" value="{{ isset($withdrawal) ? $withdrawal->sender->address : Request::old('sender') }}">
      </div>
      <div class="form-group">
        <label for="recipient">Recipient Address (User NEM Address)</label>
        <input type="text" class="form-control" id="recipient" name="recipient" value="{{ isset($withdrawal) ? $withdrawal->recipient_address : Request::old('recipient') }}">
      </div>
      <div class="form-group">
        <label for="mosaic_fqmn">NEM Mosaic</label>
        <input type="text" class="form-control" id="mosaic_fqmn" name="mosaic_fqmn" value="{{ isset($withdrawal) ? $withdrawal->mosaic_fqmn : Request::old('mosaic_fqmn') }}">
      </div>
      <div class="form-group">
        <label for="amount">Amount (smallest possible unit. Ex. : 1 XEM = 1000000 nem:xem)</label>
        <input type="text" class="form-control" id="amount" name="amount" value="{{ isset($withdrawal) ? $withdrawal->amount : Request::old('amount') }}">
      </div>
      <div class="form-group">
        <label for="reference">Transaction Message (Optional)</label>
        <input type="text" class="form-control" id="reference"  name="reference" value="{{ isset($withdrawal) ? $withdrawal->reference : Request::old('reference') }}">
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