<?php

return [
    'monstersDb' => env('TERA_MONSTERS', 'monsters/monsters-EU-EN.xml'),
    'hotdotDb' => env('TERA_HOTDOT', 'hotdot/hotdot-EU-EN.tsv'),
    'skillsDb' => env('TERA_SKILLS', 'skills/skills-EU-EN.tsv'),
    'allowedRegions' => [
        126, // Pit of Petrax
        735, // RK-9 Kennel
        935, // RK-9 Kennel (Hard)
        950, // Harrowhold
        794, // Thaumetal Refinery
        994, // Thaumetal Refinery (Hard)
        716, // Sky Cruiser Endeavor
        916, // Sky Cruiser Endeavor (Hard)
        769, // Lilith's Keep
        969, // Lilith's Keep (Hard)
        460, // Kalivan's Dreadnaught (Hard)
        760, // Kalivan's Dreadnaught
        710, // Broken Prison
        810, // Lakan's Prison
        770, // Ruinous Manor
        970, // Ruinous Manor (Hard)
        813, // Ghillieglade (1-Person)
        713, // Ghillieglade
        911, // Kelsaik's Raid (10-Person)
        975, // Kelsaik's Raid (20-Person)
    ],
];
