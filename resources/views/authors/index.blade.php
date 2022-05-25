@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <table class="table table-responsive text-center">
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Place of birth</th>
                <th>Actions</th>
            </tr>
            @foreach($authors as $author)
                <tr>
                    <td>{{ $author['first_name'] }}</td>
                    <td>{{ $author['last_name'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($author['birthday'])->format('d.m.Y.') }}</td>
                    <td>{{ $author['gender'] }}</td>
                    <td>{{ $author['place_of_birth'] }}</td>
                    <td>
                        <form action="{{ route('authors.destroy', ['author' => $author['id']]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            <a href="{{ route('authors.show', ['author' => $author['id']]) }}" class="btn btn-outline-info btn-sm">View</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        @if($authors->hasMorePages() || $authors->total() > 12)
            @if($authors->count())
                <div class="d-flex">
                    <div class="mx-auto">
                        {!! $authors->links() !!}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
