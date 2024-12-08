@extends('layouts.app')

@section('content')
<body style="background: lightgray">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-50 rounded">
                        </div>
                        <div class="text-right">
                            <div class="badge badge-primary p-2">{{ $book->is_published ? 'Published' : 'Not Published' }}</div>
                        </div>
                        <hr>
                        <h4>{{ $book->name }}</h4>
                        <p class="tmt-3">
                            {!! $book->description !!}
                        </p>
                        <div class="text-right">
                            <i> Author: {{ $book->author }}</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection