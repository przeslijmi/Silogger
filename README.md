# Przeslijmi Silogger

Simple logging solution.

## Description

Receives and saves (or shows) logs.

## Hello World!

```php
$log = Log::get();
$log->notice('Hello World!');
```

## Multiple loggers

```php
// Use multiple loggers to separate calls.
$log1 = Log::get('firstLogger');
$log1->notice('Hello World!');

$log2 = Log::get('secondLogger');
$log2->notice('Hello World!');
```

## Configuration (not obligatory)

This says:
  - `firstLogger` will show all CLI logs and will save to file most important logs.
  - `secondLogger` will not show any CLI logs (level array is defined but empty), and save only emergency to file.

```php
Silogger::declare(
  'firstLogger',
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
  'secondLogger',
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
