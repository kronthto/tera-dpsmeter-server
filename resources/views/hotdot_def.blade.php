<?php
$hotdot = app('tera.data')->getHotdotById($hotdotId);
$hotdotImg = null;
if ($hotdot) {
    $hotdotImg = \App\Service\ViewHelpers::generateIco(app('tera.data')->hotdotIco($hotdot), $hotdot[8]);
}
?>

@if ($hotdot)
    <div class="popover popover-right hotdot-definition" data-hotdotid="{{ $hotdotId }}">
        {!! $hotdotImg !!} {{ $hotdot[8] }}
        <div class="popover-container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title h5">{{ $hotdot[8] }}</div>
                    <div class="card-subtitle text-gray">{{ $hotdot[1] }} {{ $hotdot[2] }}</div>
                </div>
                <div class="card-body">
                    {!! \App\Service\ViewHelpers::formatTeraDescription($hotdot[11]) !!}
                </div>
            </div>
        </div>
    </div>
@else
    {{ $hotdotId }}
@endif
