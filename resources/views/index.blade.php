@extends('layouts.app')

@section('content')
    <h1><abbr title="The Exiled Realm of Arborea">TERA</abbr> DPS Stats</h1>
    <style>
        .dpsCard {
            break-inside: avoid-column;
            text-align: left;
        }

        .columnList {
            column-count: 4;
            column-gap: 20px;
        }

        @media (max-width: 1100px) {
            .columnList {
                column-count: 3;
            }
        }

        @media (max-width: 800px) {
            .columnList {
                column-count: 2;
            }
        }

        @media (max-width: 460px) {
            .columnList {
                column-count: 1;
            }
        }
    </style>
    <section>
    <h2>Recent Best DPS by Boss</h2>
    <div class="columnList">
        @foreach($byBoss as $boss)
            <?php $encounter = reset($boss)->stat; ?>
            <div class="dpsCard" data-boss-id="{{ $encounter->boss_id }}" data-area-id="{{ $encounter->area_id }}">
                <h3>{{ $encounter->getMonsterName() }} - {{ $encounter->getAreaName() }}</h3>
                <ol>
                    @foreach(array_slice($boss, 0, 5) as $member)
                        <li data-when="{{ $member->stat->encounter_unix }}">
                            <abbr title="{{ $member->playerClass }} of {{ $member->guild ?? '-' }}, {{ $member->playerServer }}">{{ $member->playerName }}</abbr>
                            - <abbr title="{{ $member->playerDps }}">{{ \App\Stat::damageFormat($member->playerDps) }}</abbr>/s (<a
                                    href="{{ route('statDetail', $member->stat ) }}" title="{{ $encounter->getTitle() }}" target="_blank">#</a>)
                        </li>
                    @endforeach
                </ol>
            </div>
        @endforeach
    </div>
    <span>Checked stats since {{ $statsSince->toFormattedDateString() }}</span>
    </section>

    <section>
    <h2>Latest submitted encounters</h2>
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
                <td data-boss-id="{{ $encounter->boss_id }}" data-area-id="{{ $encounter->area_id }}">{{ $encounter->getMonsterName() }} - {{ $encounter->getAreaName() }}</td>
                <td>{{ gmdate('i:s', $encounter->data->fightDuration) }}</td>
                <td><abbr title="{{ $encounter->data->partyDps }}">{{ \App\Stat::damageFormat($encounter->data->partyDps)  }}</abbr></td>
                <td><a href="{{ route('statDetail', $encounter) }}" target="_blank" title="{{ $encounter->getTitle() }}">Detail</a> <a href="{{ route('getStat', $encounter) }}" target="_blank" title="JSON-Data: {{ $encounter->getTitle() }}">Raw</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </section>
@endsection
