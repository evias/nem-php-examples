@extends('layouts.app')
 
@section('moduleTitle')
    <a href="/addresses">NEM Addresses</a>
    <a href="/addresses/create">
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
            <th scope="col">BIP44 Path</th>
            <th scope="col">Public Key</th>
            <th scope="col">NEM Address</th>
            <th scope="col">Created At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($addresses as $address)
        <tr>
            <th scope="row">{{$address->id}}</th>
            <td><a href="/addresses/{{$address->id}}">{{$address->bip44_path}}</a></td>
            <td>{{substr($address->public_key, 0, 8)}}</td>
            <td>{{$address->address}}</td>
            <td>{{$address->created_at->toFormattedDateString()}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection