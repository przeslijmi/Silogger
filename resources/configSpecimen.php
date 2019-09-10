<?php declare(strict_types=1);

use Przeslijmi\Silogger\Silogger;

Silogger::declare(
  'default',
  [
    'cli' => [
    ],
    'file' => [
      'uri'    => '.logs/[Y].[m].[d].[ip].log',
      'uriRef' => '.logs/[Y].[m].[d].[ip].[ref].log',
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
  ]
);
