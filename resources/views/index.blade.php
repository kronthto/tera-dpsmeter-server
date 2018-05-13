@extends('layouts.app')

@section('content')
    <h1><abbr title="The Exiled Realm of Arborea">TERA</abbr> DPS Stats</h1>
    <section>
    <h2>Recent Best DPS by Dungeon/Boss</h2>
            @foreach($recentEvents as $recentMember)
                {{-- enrich with data- attrs --}}
            <a class="toast toast-primary" href="{{ route('statDetail', $recentMember->stat ) }}" title="{{ $recentMember->stat->getTitle() }} at {{ $recentMember->stat->encounter_unix }}">🎉
                <abbr data-guild="{{ $recentMember->guild ?? '' }}" data-name="{{ $recentMember->playerName }}"
                      data-class="{{ $recentMember->playerClass }}" data-server="{{ $recentMember->playerServer }}"
                      title="{{ $recentMember->playerClass }} of {{ $recentMember->guild ?? '-' }}, {{ $recentMember->playerServer }}">{{ $recentMember->playerName }}</abbr>
                placed <b>#{{ $recentMember->rank }}</b> at {{ $recentMember->stat->toString() }} <abbr title="{{ $recentMember->stat->encounter_unix }}">{{ $recentMember->stat->encounter_unix->diffForHumans() }}</abbr>
                🎉</a>
            @endforeach
        <br>
        <div>
            @foreach($byMap as $mapId => $mapData)
                <div class="accordion" data-area-id="{{ $mapId }}">
                    <input type="checkbox" id="accordion-radio-map-{{ $mapId }}" hidden>
                    <label class="accordion-header" for="accordion-radio-map-{{ $mapId }}" style="cursor: pointer">
                        <i class="icon icon-arrow-right mr-1"></i> {{ app('tera.data')->getAreaNameById($mapId) }}
                    </label>
                    <div class="accordion-body container">
                        <div class="columns">
                        @foreach($mapData as $bossId => $boss)
                            <div class="column col-sm-6 col-xs-12" data-boss-id="{{ $bossId }}" data-area-id="{{ $mapId }}">
                                <h3>{{ app('tera.data')->getMonsterNameByAreaAndId($mapId, $bossId) }}</h3>
                                <ol>
                                    @foreach(array_slice($boss, 0, 5) as $member)
                                        <li data-when="{{ $member->stat->encounter_unix }}">
                                            {!! \App\Service\TeraData::classIconHtml($member) !!}
                                            <abbr data-guild="{{ $member->guild ?? '' }}" data-name="{{ $member->playerName }}"
                                                  data-class="{{ $member->playerClass }}" data-server="{{ $member->playerServer }}"
                                                  title="{{ $member->playerClass }} of {{ $member->guild ?? '-' }}, {{ $member->playerServer }}">{{ $member->playerName }}</abbr>
                                            - <abbr title="{{ $member->playerDps }}">{{ \App\Stat::damageFormat($member->playerDps) }}</abbr>/s (<a
                                                    class="tooltip" data-tooltip="{{ $member->stat->encounter_unix }}"
                                                    href="{{ route('statDetail', $member->stat ) }}" title="{{ $member->stat->getTitle() }} at {{ $member->stat->encounter_unix }}">#</a>)
                                            @if($member->stat->isRecent())
                                                <i class="tooltip" data-tooltip="{{ $member->stat->encounter_unix->diffForHumans() }}">⚡</i>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
        </div>
    <span style="font-size: 80%; font-style: italic;">Checked stats since {{ $statsSince->toFormattedDateString() }}</span>
    </section>

    <section>
    <h2>Latest submitted encounters</h2>
    <table class="table table-striped">
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
                <td><a href="{{ route('statDetail', $encounter) }}" title="{{ $encounter->getTitle() }} at {{ $encounter->encounter_unix }}">Detail</a> <a href="{{ route('getStat', $encounter) }}" target="_blank" title="JSON-Data: {{ $encounter->getTitle() }} at {{ $encounter->encounter_unix }}">Raw</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </section>
@endsection
