@extends('layouts.app')
 
@section('moduleTitle')
    <a href="/withdrawals">User Withdrawals</a>
    <a href="/withdrawals/create">
        <button type="button" class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>&nbsp;Create</button>
    </a>
@endsection

@section('content')
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">NEM Addresses</th>
            <th scope="col">NEM Amount</th>
            <th scope="col">Broadcast #</th>
            <th scope="col">Created At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($withdrawals as $withdrawal)
        <tr>
            <th scope="row">{{$withdrawal->id}}</th>
            <td>
                Sender: <a href="/addresses/{{$withdrawal->address_id}}">{{$withdrawal->sender->address}}</a><br />
                Recipient: <a href="/withdrawals/{{$withdrawal->id}}">{{$withdrawal->recipient_address}}</a>
            </td>
            <td>{{$withdrawal->amount}} {{$withdrawal->mosaic_fqmn}}</td>
            <td>{{$withdrawal->broadcast_height ?: "N/A"}}</td>
            <td>{{$withdrawal->created_at->toFormattedDateString()}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection