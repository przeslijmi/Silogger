<?php declare(strict_types=1);

use Przeslijmi\Silogger\Silogger;

define('PRZESLIJMI_SILOGGER_LOCALE_URIS', [
  'en:us' => [
    'Class\Test' => 'resources/localesForTests/en-us.php',
  ],
]);

Silogger::declare(
  'default',
  [
    'cli' => [
      'levels' => [
        Silogger::EMERGENCY,
        Silogger::ALERT,
        Silogger::CRITICAL,
        Silogger::ERROR,
        Silogger::WARNING,
        Silogger::NOTICE,
        Silogger::INFO,
        Silogger::DEBUG
      ]
    ],
    'file' => [
      'uri'    => '.logs/[Y].[m].[d].[ip].log',
      'uriRef' => '.logs/refs/[Y].[m].[d].[ip].[ref].log',
      'format' => '[Y]-[m]-[d]-[H]-[i]-[s] [lvl]: [msg]',
      'levels' => [
        Silogger::EMERGENCY,
        Silogger::ALERT,
        Silogger::CRITICAL,
        Silogger::ERROR,
        Silogger::WARNING,
        Silogger::NOTICE,
        Silogger::INFO,
        Silogger::DEBUG
      ]
    ],
    'flow' => [
      'levels' => [
        Silogger::DEBUG
      ]
    ],
  ]
);
