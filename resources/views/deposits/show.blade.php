@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/deposits">User Deposits</a>
 <a href="/deposits/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Showing Deposit #{{$deposit->nonce}} for User {{ $deposit->user->email }}</h1>
 
    <div class="jumbotron text-center">
        <p>
            <strong>Reference:</strong> {{ $deposit->reference }}<br>
            <strong>NEM Mosaic:</strong> {{ $deposit->mosaic_fqmn }}<br />
            <strong>Total Amount:</strong> {{ $deposit->awaited_amount }} {{ $deposit->mosaic_fqmn }}<br />
            <strong>Paid Amount:</strong> {{ $deposit->paid_amount }} {{ $deposit->mosaic_fqmn }}<br />
            <strong>Pending Amount:</strong> {{ $deposit->pending_amount }} {{ $deposit->mosaic_fqmn }}<br />
            <strong>Creation Height:</strong> {{ $deposit->creation_height ?: "N/A" }}<br />
        </p>
    </div>
@endsection