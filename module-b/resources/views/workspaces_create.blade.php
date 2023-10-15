@extends('layout.main')


@section('title')
    <h1>Workspaces</h1>
@endsection

@section('content')
    <form action="{{ @route('workspaces.create.process') }}" method="POST">
        @csrf
        <h1>Create workspace</h1>
        <br>
        <div>
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
            @endforeach
        </div>
        <label>
            <small>Title</small>
            <br>
            <input type="text" name="title" placeholder="Type title">
        </label>
        <br>
        <label>
            <small>Description</small>
            <br>
            <input type="text" name="description" placeholder="Type description">
        </label>
        <br>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection

<style>

    form {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin-top: 5rem;
    }
</style>
