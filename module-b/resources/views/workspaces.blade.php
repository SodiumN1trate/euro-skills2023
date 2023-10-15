@extends('layout.main')


@section('title')
    <h1>Workspaces</h1>
@endsection

@section('content')
    <a class="btn btn-primary" href="{{ route('workspaces.create') }}">Create +</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">title</th>
            <th scope="col">Handle</th>
        </tr>
        </thead>
        <tbody>
        @foreach($workspaces as $workspace)
            <tr>
                <th scope="row">{{ $workspace->id }}</th>
                <td>{{ $workspace->title }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('workspaces.show', ['id' => $workspace->id]) }}">Open</a>
                    <a class="btn btn-secondary" href="{{ route('workspaces.bill.show', ['id' => $workspace->id]) }}">Bill</a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection
