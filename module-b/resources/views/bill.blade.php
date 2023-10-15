@extends('layout.main')


@section('title')
    <h1>Bill</h1>
@endsection

@section('content')
    @php
        $total = 0;
    @endphp
    <form class="w-50" action="{{ route('workspaces.bill.show', ['id' => $workspace->id]) }}" method="GET">
        @csrf
        <select name="month" class="form-select" aria-label="Default select example">
            <option selected>Select month</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <br>
        <button class="btn btn-secondary">Filter</button>
    </form>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Token</th>
            <th scope="col">Time</th>
            <th scope="col">Per sec.</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($workspace->tokens as $token)
            @foreach($token->usages as $usage)
                @if(isset($month) && explode('-', $usage->started_at)[1] === $month)
                    <tr>
                        <th><b>{{ $token->name }}</b> ({{ $usage->service->name }})</th>
                        <td>{{ $usage->duration / 1000 }} s</td>
                        <td>{{ $usage->service->cost_per_ms }}</td>
                        <td>
                            @php
                                $total += round($usage->service->cost_per_ms * $usage->duration, 2)
                            @endphp
                            $ {{ round($usage->service->cost_per_ms * $usage->duration, 2) }}</td>
                    </tr>
                @endif
                @if(!isset($month) && explode('-', $usage->started_at)[1] !== $month)
                    <tr>
                        <th><b>{{ $token->name }}</b> ({{ $usage->service->name }})</th>
                        <td>{{ $usage->duration / 1000 }} s</td>
                        <td>{{ $usage->service->cost_per_ms }}</td>
                        <td>
                            @php
                                $total += round($usage->service->cost_per_ms * $usage->duration, 2)
                            @endphp
                            $ {{ round($usage->service->cost_per_ms * $usage->duration, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        </tbody>
    </table>
    <h2>Total: {{round($total, 2) }}</h2>
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
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
    }

    .token-form {
        margin-top: 1rem;
    }
</style>
