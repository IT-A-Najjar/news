<?php

// $capabilities = [
//     'local/news:managenewsadd' => [
//         'riskbitmask' => RISK_SPAM,
//         'captype' => 'write',
//         'contextlevel' => CONTEXT_SYSTEM,
//         'archetypes' => [
//             'user_news' => CAP_ALLOW,
//         ],
//     ],
// ];
$capabilities = [
    'local/news:managenews' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'user_news' => CAP_ALLOW,
        ],
    ],
];