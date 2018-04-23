@extends('layouts.app')
 
@section('moduleTitle')
    <a href="/deposits">User Deposits</a>
    <a href="/deposits/create">
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
            <th scope="col">Email address</th>
            <th scope="col">Reference</th>
            <th scope="col">NEM Mosaic</th>
            <th scope="col">Paid Amount</th>
            <th scope="col">Pending Amount</th>
            <th scope="col">Created At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($deposits as $deposit)
        <tr>
            <th scope="row">{{$deposit->id}}</th>
            <td><a href="/users/{{$deposit->user_id}}">{{$deposit->user->email}}</a></td>
            <td><a href="/deposits/{{$deposit->id}}">{{$deposit->reference}}</a></td>
            <td>{{$deposit->mosaic_fqmn}}</td>
            <td>{{$deposit->paid_amount}} / {{$deposit->awaited_amount}}</td>
            <td>{{$deposit->pending_amount}}</td>
            <td>{{$deposit->created_at->toFormattedDateString()}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection