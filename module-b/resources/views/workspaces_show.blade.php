@php use Illuminate\Support\Str; @endphp
@extends('layout.main')


@section('title')
    <h1>Workspaces</h1>
@endsection

@section('content')
    <div class="content">
        <form action="{{ @route('workspaces.update.process', ['id' => $workspace->id]) }}" method="POST">
            @csrf
            <h2>Edit workspace</h2>
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
                <input type="text" name="title" placeholder="Type title" value="{{ $workspace->title }}">
            </label>
            <br>
            <label>
                <small>Description</small>
                <br>
                <input type="text" name="description" placeholder="Type description"
                       value="{{ $workspace->description }}">
            </label>
            <br>
            <label>
                <small>Billing quota</small>
                <br>
                <select name="quota_id" class="form-select form-select-lg mb-3" aria-label="Large select example">
                    <option value="null">Unset</option>
                    @foreach($quotas as $quota)
                        @if($quota->id === $workspace->quota_id)
                            <option selected value="{{ $quota->id }}">{{ $quota->limit }}</option>
                        @else
                            <option value="{{ $quota->id }}">{{ $quota->limit }}</option>
                        @endif
                    @endforeach
                </select>
            </label>
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <br>
        <br>
        <h2>API Tokens</h2>
        <br>
        <h3>Add token</h3>
        <form class="token-form" action="{{ @route('token.create', ['id' => $workspace->id]) }}" method="POST">
            @csrf
            <div>
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
            <label>
                <small>Name</small>
                <br>
                <input type="text" name="name" placeholder="Type name">
            </label>
            <br>
            <label>
                <small>Token</small>
                <br>
                <input type="text" name="token" placeholder="Type token" value="{{ Str::random(40) }}">
                <br>
                <small style="color: red;">Copy it</small>
            </label>
            <br>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        @if(count($workspace->tokens) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Revoked at</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                @foreach($workspace->tokens as $token)
                    <tr>
                        <th scope="row">{{ $token->id }}</th>
                        <td>{{ $token->name }}</td>
                        <td>{{ $token->created_at }}</td>
                        <td>{{ $token->revocation_date }}</td>
                        <td>@if(!isset($token->revocation_date))
                                <a class="btn btn-danger" href="{{ route('token.revoke', ['id' => $token->id]) }}">Revoke</a>
                            @endif</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @else
            <p>There is no tokens :(</p>
        @endif

    </div>
@endsection


<style>
    .content {
        margin-top: 5rem;
        text-align: center;
    }

    .table {
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        margin-top: 2rem;
    }

    form {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin-top: 5rem;
    }

    .token-form {
        margin-top: 1rem;
    }
</style>
