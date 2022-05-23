@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <table class="table table-responsive">
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Place of birth</th>
            </tr>
            @foreach($authors as $author)
                <tr>
                    <td>{{ $author['first_name'] }}</td>
                    <td>{{ $author['last_name'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($author['birthday'])->format('d.m.Y.') }}</td>
                    <td>{{ $author['gender'] }}</td>
                    <td>{{ $author['place_of_birth'] }}</td>
                </tr>
            @endforeach
        </table>

        @if($authors->hasMorePages() || $authors->total() > 12)
            @if($authors->count())
                <div class="card-block">
                    {!! $authors->links() !!}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
