@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12">
                <h1>Add new book</h1>

                <form action="{{ route('books.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="releaseDate" class="form-label">Release date</label>
                        <input type="date" class="form-control" id="releaseDate">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn">
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format</label>
                        <input type="text" class="form-control" id="format">
                    </div>
                    <div class="mb-3">
                        <label for="numberOfPages" class="form-label">Number of pages</label>
                        <input type="number" class="form-control" id="numberOfPages">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
