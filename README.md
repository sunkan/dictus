# Sunkan Dictus - Date utility

[![Latest Version](https://img.shields.io/github/release/sunkan/dictus.svg?style=flat-square)](https://github.com/sunkan/dictus/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This is a small library to help with date formatting and keeping a consistent clock in a project

## Usage

### Using formatters

```php

use Sunkan\Dictus\DateTimeFormat;
use Sunkan\Dictus\DateTimeFormatter;
use Sunkan\Dictus\LocalizedFormat;
use Sunkan\Dictus\LocalizedFormatter;

$jsonDateFormatter = new DateTimeFormatter(DateTimeFormat::JSON);
$localizedDateFormatter = new LocalizedFormatter('sv_SE', LocalizedFormat::DATETIME);

$date = new DateTimeImmutable();

echo $jsonDateFormatter->format($date);
echo $localizedDateFormatter->format($date);
```
### Clock

The library provides 3 clock implementations

`SystemClock, FrozenClock and ProjectClock`

`SystemClock` is just a wrapper around `new DateTimeImmutable('now')`

`FrozenClock` is a wrapper around a fixed `DateTimeImmutable` object

`ProjectClock` is wrapper around a `ClockInterface` implementation
