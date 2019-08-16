# Przeslijmi Silogger

Simple logging solution.

## Description

Receives and saves (or shows) logs.

## Hello World!

```php
// Create/get log.
$log = Log::get();

// Direct calls.
$log->emergency('Hello World!');
$log->alert('Hello World!');
$log->critical('Hello World!');
$log->error('Hello World!');
$log->warning('Hello World!');
$log->notice('Hello World!');
$log->info('Hello World!');
$log->debug('Hello World!');

// Standard call.
$log->log('info', 'Hello World!');
```

## Multiple loggers

```php
// Use first Log for some reasons.
$log1 = Log::get('firstLog');
$log1->notice('Hello World!');

// Use second Log for other reasons.
$log2 = Log::get('secondLog');
$log2->notice('Hello World!');
```

## Configuration (not obligatory)

Configuration is not obligatory. First Log is created automatically (is called `default`). Logging to this Log causes only to show logs on CLI screen. They are not saved in any way.

Below configuration says:
  - `firstLog` will show all CLI logs and will save to file most important logs.
  - `secondLog` will not show any CLI logs (`level` array is defined but empty), and save only `emergency` to file.

```php
Silogger::declare(
  'firstLog',
  [
    'cli' => [
    ],
    'file' => [
      'uri'    => 'logs/[ip].log',
      'format' => '[Y]-[m]-[d]-[H]-[i]-[s] [lvl]: [msg]',
      'levels' => [
        Silogger::EMERGENCY,
        Silogger::ALERT,
        Silogger::CRITICAL,
        Silogger::ERROR
      ]
    ],
  ],
  'secondLog',
  [
    'cli' => [
      'levels' => [
      ]
    ],
    'file' => [
      'uri'    => 'logs/[ip].log',
      'format' => '[Y]-[m]-[d]-[H]-[i]-[s] [lvl]: [msg]',
      'levels' => [
        Silogger::EMERGENCY,
      ]
    ],
  ]
);
```

## Formats

### Formats with one letter
These are all taken from https://www.php.net/manual/en/function.date.php function format.

### Other formats
- `[ip]` IP of caller
- `[lvl]` Level (lowercased)
- `[LVL]` Level (uppercased)
- `[msg]` Log message contents
