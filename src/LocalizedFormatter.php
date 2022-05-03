<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use DateTimeImmutable;
use IntlDateFormatter;

final class LocalizedFormatter implements Formatter
{
	public function __construct(
		private string $local,
		private string $format,
	) {}

	public function format(\DateTimeInterface $date): string
	{
		$date = DateTimeImmutable::createFromInterface($date);
		return $this->strftime($this->format, $date, $this->local);
	}

	private function strftime(string $format, DateTimeImmutable $timestamp, string $local): string
	{
		// remove trailing part not supported by ext-intl locale
		$locale = preg_replace('/[^\w-].*$/', '', $local);

		$intlFormats = [
			'%a' => 'EEE',    // An abbreviated textual representation of the day	Sun through Sat
			'%A' => 'EEEE',    // A full textual representation of the day	Sunday through Saturday
			'%b' => 'MMM',    // Abbreviated month name, based on the locale	Jan through Dec
			'%B' => 'MMMM',    // Full month name, based on the locale	January through December
			'%h' => 'MMM',    // Abbreviated month name, based on the locale (an alias of %b)	Jan through Dec
		];

		$intlFormatter = static function (DateTimeImmutable $timestamp, string $format) use ($intlFormats, $locale) {
			$dateType = IntlDateFormatter::FULL;
			$timeType = IntlDateFormatter::FULL;
			$pattern = '';

			switch ($format) {
				// %c = Preferred date and time stamp based on locale
				// Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
				case '%c':
					$dateType = IntlDateFormatter::LONG;
					$timeType = IntlDateFormatter::SHORT;
					break;

				// %x = Preferred date representation based on locale, without the time
				// Example: 02/05/09 for February 5, 2009
				case '%x':
					$dateType = IntlDateFormatter::SHORT;
					$timeType = IntlDateFormatter::NONE;
					break;

				// Localized time format
				case '%X':
					$dateType = IntlDateFormatter::NONE;
					$timeType = IntlDateFormatter::MEDIUM;
					break;

				default:
					$pattern = $intlFormats[$format];
			}

			return (new IntlDateFormatter($locale, $dateType, $timeType, $timestamp->getTimezone(), null, $pattern))->format($timestamp);
		};

		$spacePadding = static function (int $padding) {
			return static fn(string $val) => sprintf('% ' . $padding . 'u', $val);
		};
		$dateFormat = static function (string $format, callable $modifier = null) {
			if (!$modifier) {
				$modifier = static fn ($v) => $v;
			}
			return static fn(DateTimeImmutable $d) => $modifier($d->format($format));
		};

		/** @var array<string, string|callable(DateTimeImmutable $date, string $pattern): string> */
		$translationTable = [
			// Day
			'%a' => $intlFormatter,
			'%A' => $intlFormatter,
			'%d' => 'd',
			'%e' => $dateFormat('j', $spacePadding(2)),
			'%j' => function (DateTimeImmutable $timestamp) {
				// Day number in year, 001 to 366
				return sprintf('%03d', (int)$timestamp->format('z') + 1);
			},
			'%u' => 'N',
			'%w' => 'w',

			// Week
			'%U' => function (DateTimeImmutable $timestamp) {
				// Number of weeks between date and first Sunday of year
				$currentDay = (int)$timestamp->format('z');
				$firstDay = (int)$timestamp->modify('first sunday of january')->format('z');
				return sprintf('%02u', 1 + ($currentDay - $firstDay) / 7);
			},
			'%V' => 'W',
			'%W' => function ($timestamp) {
				// Number of weeks between date and first Monday of year
				$currentDay = (int)$timestamp->format('z');
				$firstDay = (int)$timestamp->modify('first monday of january')->format('z');
				return sprintf('%02u', 1 + ($currentDay - $firstDay) / 7);
			},

			// Month
			'%b' => $intlFormatter,
			'%B' => $intlFormatter,
			'%h' => $intlFormatter,
			'%m' => 'm',

			// Year - Century (-1): 19 for 20th century
			'%C' => $dateFormat('Y', static fn ($v) => floor($v / 100)),
			'%g' => $dateFormat('o', static fn ($v) => substr($v, -2)),
			'%G' => 'o',
			'%y' => 'y',
			'%Y' => 'Y',

			// Time
			'%H' => 'H',
			'%k' => $dateFormat('G', $spacePadding(2)),
			'%I' => 'h',
			'%l' => $dateFormat('g', $spacePadding(2)),
			'%M' => 'i',
			'%p' => 'A', // AM PM (this is reversed on purpose!)
			'%P' => 'a', // am pm
			'%r' => 'h:i:s A', // %I:%M:%S %p
			'%R' => 'H:i', // %H:%M
			'%S' => 's',
			'%T' => 'H:i:s', // %H:%M:%S
			'%X' => $intlFormatter, // Preferred time representation based on locale, without the date

			// Timezone
			'%z' => 'O',
			'%Z' => 'T',

			// Time and Date Stamps
			'%c' => $intlFormatter,
			'%D' => 'm/d/Y',
			'%F' => 'Y-m-d',
			'%s' => 'U',
			'%x' => $intlFormatter,
		];

		/** @var string $out */
		$out = preg_replace_callback('/(?<!%)%([_#-]?)([a-zA-Z])/', static function ($match) use ($translationTable, $timestamp) {
			[,$prefix, $char] = $match;
			$pattern = '%' . $char;

			if ($pattern === '%n') {
				return "\n";
			}

			if ($pattern === '%t') {
				return "\t";
			}

			if (!isset($translationTable[$pattern])) {
				throw new \InvalidArgumentException(sprintf('Format "%s" is unknown in time format', $pattern));
			}

			$replace = $translationTable[$pattern];

			if (is_callable($replace)) {
				$result = $replace($timestamp, $pattern);
			}
			else {
				$result = $timestamp->format($replace);
			}

			switch ($prefix) {
				case '_':
					// replace leading zeros with spaces but keep last char if also zero
					return preg_replace('/\G0(?=.)/', ' ', $result);
				case '#':
				case '-':
					// remove leading zeros but keep last char if also zero
					return preg_replace('/^0+(?=.)/', '', $result);
			}

			return $result;
		}, $format);

		return str_replace('%%', '%', $out);
	}

}
