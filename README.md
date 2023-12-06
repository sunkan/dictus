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
use Sunkan\Dictus\LocalizedStrftimeFormatter;
use Sunkan\Dictus\LocalizedDateTimeFormatter;

LocalizedDateTimeFormatter::addLocaleFormat('sv_SE', new \Sunkan\Dictus\Locales\SvSe());

$jsonDateFormatter = new DateTimeFormatter(DateTimeFormat::JSON);
$localizedStrftimeFormatter = new LocalizedStrftimeFormatter('sv_SE', LocalizedFormat::DATETIME);
$localizedDateTimeFormatter = new LocalizedDateTimeFormatter('sv_SE', 'l d F [kl.] H:i');

$date = new DateTimeImmutable();

echo $jsonDateFormatter->format($date);
echo $localizedStrftimeFormatterFormatter->format($date); // 21.08.2023 16:26
echo $localizedDateTimeFormatter->format($date); // måndag 21 augusti kl. 16:26
```

### Using localized formatters

We recommend using `LocalizedDateTimeFormatter` that just support translating of 
the regular [date format syntax](https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters). 

It will translate the following keys:

* `D` short version of day name
* `l` long version of day name
* `M` short version of month name
* `F` long version of month name

It also supports 6 custom keys that helps with common localized formats

Example for icelandic
```php
'LT'   => 'H:i',    // 17:32
'LTS'  => 'H:i:s',  // 17:32:12
'L'    => 'd.m.Y',  // 06.12.2023
'LL'   => 'j. F Y', // 6. desember 2023
'LLL'  => 'j. F [kl.] H:i',     // 6. desember kl. 17:32
'LLLL' => 'l j. F Y [kl.] H:i', // miðvikudagur 6. desember 2023 kl. 17:32
```

#### Custom localization

All you need to do to create your own localization is to create a class that's implements `LocaleFormat`

```php

final class CustomLocal implements \Sunkan\Dictus\LocaleFormat
 {
	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string
	{
		return match ($char) {
			'D' => 'short custom weekday name',
			'l' => 'long custom weekday name',
			'M' => null, // use english month names
			'F' => null, // use english month names
			default => null,
		};
	}

	public function resolveFormat(string $format): ?string
	{
		return match($format) {
			'LT' => 'H:i', // can be any valid date format string
			'LTS' => 'H:i:s', // can be any valid date format string
			'L' => 'd.m.Y', // can be any valid date format string
			'LL' => 'j. F Y', // can be any valid date format string
			'LLL' => 'j. F [kl.] H:i', // can be any valid date format string
			'LLLL' => 'l j. F Y [kl.] H:i', // can be any valid date format string
			default => null,
		};
	}
}
```

### Clock

The library provides 3 clock implementations

`SystemClock, FrozenClock and ProjectClock`

`SystemClock` is just a wrapper around `new DateTimeImmutable('now')`

`FrozenClock` is a wrapper around a fixed `DateTimeImmutable` object

`ProjectClock` is wrapper around a `ClockInterface` implementation
