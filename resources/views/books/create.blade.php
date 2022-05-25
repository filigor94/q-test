@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12">
                <h1>Add new book</h1>

                <form action="{{ route('books.store') }}" method="post">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="authorId" class="form-label">Author</label>
                        <select class="js-example-basic-single form-select" name="author_id" id="authorId"></select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3">
                        <label for="releaseDate" class="form-label">Release date</label>
                        <input type="date" class="form-control" id="releaseDate" name="release_date"  value="{{ old('release_date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}">
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format</label>
                        <input type="text" class="form-control" id="format" name="format" value="{{ old('format') }}">
                    </div>
                    <div class="mb-3">
                        <label for="numberOfPages" class="form-label">Number of pages</label>
                        <input type="number" class="form-control" id="numberOfPages" name="number_of_pages" value="{{ old('number_of_pages') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#authorId').select2({
            placeholder: 'Author',
            ajax: {
                url: '{{ route('authors.index') }}',
                dataType: 'json',
                data: function (params) {
                    return  {
                        search: params.term,
                        page: params.page || 1
                    }
                },
            }
        });
    </script>
@endsection
