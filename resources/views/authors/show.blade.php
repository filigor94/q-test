@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12">
                <h1>{{ $author['first_name'].' '.$author['last_name'] }}</h1>

                <table class="table table-responsive">
                    <tr>
                        <th>Birthday</th>
                        <td>{{ \Carbon\Carbon::parse($author['birthday'])->format('d.m.Y.') }}</td>
                    </tr>
                    <tr>
                        <th>Biography</th>
                        <td>{{ $author['biography'] ?? null }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $author['gender'] }}</td>
                    </tr>
                    <tr>
                        <th>Place of birth</th>
                        <td>{{ $author['place_of_birth'] }}</td>
                    </tr>
                    <tr>
                        <th>Actions</th>
                        <td>
                            <form action="{{ route('authors.destroy', ['author' => $author['id']]) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <h1>Books</h1>
                <table class="table table-responsive">
                    <tr>
                        <th>Title</th>
                        <th>Release date</th>
                        <th>Description</th>
                        <th>ISBN</th>
                        <th>Format</th>
                        <th>Number of pages</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($author['books'] as $book)
                        <tr>
                            <td>{{ $book['title'] }}</td>
                            <td>{{ isset($book['release_date']) ? \Carbon\Carbon::parse($book['release_date'])->format('d.m.Y.') : null }}</td>
                            <td>{{ $book['description'] }}</td>
                            <td>{{ $book['isbn'] }}</td>
                            <td>{{ $book['format'] }}</td>
                            <td>{{ $book['number_of_pages'] }}</td>
                            <td>
                                <form action="{{ route('books.destroy', ['book' => $book['id']]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
