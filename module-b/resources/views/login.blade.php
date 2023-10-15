@extends('layout.main')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <h1>Log in</h1>
        <br>
        <div>
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
            @endforeach
        </div>
        <label>
            <small>Username</small>
            <br>
            <input type="text" name="username" placeholder="Type your username">
        </label>
        <br>
        <label>
            <small>Password</small>
            <br>
            <input type="password" name="password" placeholder="Type your password">
        </label>
        <br>
        <button type="submit" class="btn btn-primary">Log in</button>
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
