@extends('layouts.app')

@section('title', $stat->getTitle())

@section('meta')
    <meta name="dcterms.created" content="{{ $stat->encounter_unix->toIso8601String() }}"/>
@endsection

@section('content')
    <h1 data-boss-id="{{ $stat->boss_id }}" data-area-id="{{$stat->area_id}}"
        data-when="{{ $stat->encounter_unix }}">{{ $stat->getTitle() }}</h1>
    <span data-area-id="{{$stat->area_id}}">{{ $stat->getAreaName() }}</span><br>
    <small><a href="{{ route('getStat', $stat) }}" target="_blank" title="JSON-Data: {{ $stat->getTitle() }}">Raw</a>
    </small>

    <section>
        <h2>Meta</h2>
        <dl>
            <dt>Duration</dt>
            <dd>{{ gmdate('i:s', $stat->data->fightDuration) }}</dd>
            <dt>Party DPS</dt>
            <dd><abbr title="{{ $stat->data->partyDps }}">{{ \App\Stat::damageFormat($stat->data->partyDps)  }}</abbr>
            </dd>
            @foreach($stat->data->debuffUptime as $debuff)
                <?php
                $hotdot = app('tera.data')->getHotdotById((int)$debuff->Key);
                ?>
                <dt data-key="{{ $debuff->Key }}">Debuff
                    Uptime: {!! $hotdot ? '<abbr title="'.e($hotdot[11]).'">'.e($hotdot[8]).'</abbr>' : e($debuff->Key) !!}</dt>
                <dd data-value="{{ $debuff->Value }}">{{ $debuff->Value }} {{ $hotdot ? $hotdot[4] : null }}</dd>
            @endforeach
        </dl>
    </section>

    <h2>Party Members</h2>
    @foreach($stat->data->members as $member)
        <details class="accordion">
            <summary class="accordion-header">
                <h3>
                    <i class="icon icon-arrow-right mr-1"></i> <abbr data-guild="{{ $member->guild ?? '' }}"
                                                                     data-name="{{ $member->playerName }}"
                                                                     data-class="{{ $member->playerClass }}"
                                                                     data-server="{{ $member->playerServer }}"
                                                                     title="{{ $member->playerClass }} of {{ $member->guild ?? '-' }}, {{ $member->playerServer }}">{{ $member->playerName }}</abbr>
                </h3>
            </summary>
            <div class="accordion-body muchcontent">
                <h4>Meta</h4>
                <dl>
                    <dt>DPS</dt>
                    <dd><abbr title="{{ $member->playerDps }}">{{ \App\Stat::damageFormat($member->playerDps)  }}</abbr>
                    </dd>
                    <dt>Aggro</dt>
                    <dd>{{ $member->aggro }}</dd>
                    <dt>Crit Rate %</dt>
                    <dd>{{ $member->playerAverageCritRate }}</dd>
                    <dt>Deaths</dt>
                    <dd>{{ $member->playerDeaths }}</dd>
                    <dt>Time spent dead</dt>
                    <dd>{{ $member->playerDeathDuration }}</dd>
                    <dt>Total damage</dt>
                    <dd>
                        <abbr title="{{ $member->playerTotalDamage }}">{{ \App\Stat::damageFormat($member->playerTotalDamage)  }}</abbr>
                    </dd>
                    <dt>Total damage %</dt>
                    <dd>{{ $member->playerTotalDamagePercentage }}</dd>
                </dl>

                <h4>Skills</h4>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Skill</th>
                        <th>Hits</th>
                        <th>Crit Rate %</th>
                        <th>Damage %</th>
                        <th>Highest crit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($member->skillLog as $skill)
                        <?php
                        $skillDb = app('tera.data')->getSkillById((int)$skill->skillId);
                        ?>
                        <tr>
                            <td data-id="{{ $skill->skillId }}">{{ $skillDb ? $skillDb[4] : $skill->skillId }}</td>
                            <td>{{ $skill->skillHits }}</td>
                            <td>{{ $skill->skillCritRate }}</td>
                            <td>{{ $skill->skillDamagePercent }}</td>
                            <td>
                                <abbr title="{{ $skill->skillHighestCrit }}">{{ \App\Stat::damageFormat($skill->skillHighestCrit)  }}</abbr>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <h4>Buffs Uptime</h4>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Buff</th>
                        <th>Uptime</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($member->buffUptime as $buff)
                        <?php
                        $hotdot = app('tera.data')->getHotdotById((int)$buff->Key);
                        ?>
                        <tr>
                            <td data-key="{{ $buff->Key }}">{!! $hotdot ? '<abbr title="'.e($hotdot[11]).'">'.e($hotdot[8]).'</abbr>' : e($buff->Key) !!}</td>
                            <td data-value="{{ $buff->Value }}">{{ $buff->Value }} {{ $hotdot ? $hotdot[4] : null }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @endforeach
@endsection
