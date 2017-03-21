@extends('layouts.app')

@section('content')
    <table style="width:100%;table-layout:fixed">
        <thead>
        <tr>
            <th>When</th>
            <th>Boss</th>
            <th>Duration</th>
            <th>Party DPS</th>
            <th>Data</th>
        </tr>
        </thead>
        <tbody>
        @foreach($encounters as $encounter)
            <tr>
                <td>{{ $encounter->encounter_unix }}</td>
                <td>{{ $encounter->getMonsterName() }} - {{ $encounter->getAreaName() }}</td>
                <td>{{ gmdate('i:s', $encounter->data->fightDuration) }}</td>
                <td>{{ \App\Stat::damageFormat($encounter->data->partyDps)  }}</td>
                <td><a href="{{ route('getStat', $encounter) }}" target="_blank">Raw</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
