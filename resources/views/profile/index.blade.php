@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5 text-center">
            <div class="col-12">
                <h1>Hello, {{ auth()->user()->additional_data['user']['first_name'].' '.auth()->user()->additional_data['user']['last_name'] }}</h1>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn">Logout</button>
                </form>
            </div>
        </div>
    </div>
@endsection
