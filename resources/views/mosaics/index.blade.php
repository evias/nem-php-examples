@extends('layouts.app')
 
@section('moduleTitle')
<a href="/mosaics">Currencies Management</a>
<a href="/mosaics/create">
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
            <th scope="col">FQMN</th>
            <th scope="col">Namespace</th>
            <th scope="col">Name</th>
            <th scope="col">Created At</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mosaics as $mosaic)
        <tr>
            <th scope="row"><strong>{{$mosaic->fqmn}}</strong></th>
            <td>{{$mosaic->namespace}}</td>
            <td>{{$mosaic->mosaic_name}}</td>
            <td>{{$mosaic->created_at->toFormattedDateString()}}</td>
            <td>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <div class="col-md-6">
                    <form action="{{url('mosaics', [$mosaic->id])}}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-sm btn-danger" value="Delete"/>
                    </form>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('contentFooter')
    <div>{{ $mosaics->links('vendor.pagination.bootstrap-4') }}</div>
@endsection
