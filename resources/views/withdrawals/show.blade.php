@extends('layouts.app')
 
 @section('moduleTitle')
 <a href="/withdrawals">User Withdrawals</a>
 <a href="/withdrawals/create">
     <button type="button" class="btn btn-sm btn-success">
         <i class="fa fa-plus"></i>&nbsp;Create</button>
 </a>
 @endsection
 
@section('content')
    <h1>Showing Withdrawal #{{$withdrawal->nonce}} for User {{ $withdrawal->user->email }}</h1>
 
    <div class="jumbotron text-center">
        <p>
            <strong>Sender (App):</strong> {{ $withdrawal->sender->address }}<br />
            <strong>Recipient (User):</strong> {{ $withdrawal->recipient_address }}<br />
            <strong>NEM Mosaic:</strong> {{ $withdrawal->mosaic_fqmn }}<br />
            <strong>Total Amount:</strong> {{ $withdrawal->amount }} {{ $withdrawal->mosaic_fqmn }}<br />
            <strong>Reference:</strong> {{ $withdrawal->reference ?: "N/A" }}<br>
            <strong>Broadcast Height:</strong> {{ $withdrawal->broadcast_height ?: "N/A" }}<br />
            <strong>Transaction Hash:</strong> {{ $withdrawal->tx_hash ?: "N/A" }}<br />
        </p>
    </div>
@endsection