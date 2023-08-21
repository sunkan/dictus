<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use IntlDateFormatter;

final class LocalizedDateTimeFormatter implements Formatter
{
	/**
	 * @var array<string, array{
	 *     LT: string,
	 *     LTS: string,
	 *     L: string,
	 *     LL: string,
	 *     LLL: string,
	 *     LLLL: string,
	 * }>
	 */
	private static array $localFormats = [
		'is_IS' => [
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'd.m.Y',
			'LL' => 'j. F Y',
			'LLL' => 'j. F [kl.] H:i',
			'LLLL' => 'l j. F Y [kl.] H:i',
		],
		'sv_SE' => [
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'Y-m-d',
			'LL' => 'j F Y',
			'LLL' => 'j F Y [kl.] H:i',
			'LLLL' => 'l j F Y [kl.] H:i',
		],
		'en_GB' => [
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'd/m/Y',
			'LL' => 'j F Y',
			'LLL' => 'j F Y H:i',
			'LLLL' => 'l, j F Y H:i',
		],
	];

	/**
	 * @param array{
	 *     LT: string,
	 *     LTS: string,
	 *     L: string,
	 *     LL: string,
	 *     LLL: string,
	 *     LLLL: string,
	 * } $format
	 */
	public static function addLocalFormat(string $local, array $format): void
	{
		self::$localFormats[$local] = $format;
	}

	/** @var array<string, IntlDateFormatter> */
	private static array $intlCache = [];

	public function __construct(
		private string $format,
		private string $local,
	) {}

	public function setFormat(string $format): void
	{
		$this->format = $format;
	}

	public function format(\DateTimeInterface $date): string
	{
		return $this->formatTimestamp($this->format, $date, $this->local);
	}

	function formatTimestamp(string $format, \DateTimeInterface $timestamp, string $locale): string
	{
		$dateFormatToIntlFormatMap = [
			'D' => 'EEE',
			'l' => 'EEEE',
			'F' => 'MMMM',
			'M' => 'MMM'
		];
		$result = '';
		$length = mb_strlen($format);
		$inEscaped = false;

		for ($i = 0; $i < $length; $i++) {
			$char = mb_substr($format, $i, 1);
			if ($char === '\\') {
				$result .= mb_substr($format, ++$i, 1);

				continue;
			}

			if ($char === '[' && !$inEscaped) {
				$inEscaped = true;

				continue;
			}

			if ($char === ']' && $inEscaped) {
				$inEscaped = false;

				continue;
			}

			if ($inEscaped) {
				$result .= $char;

				continue;
			}

			if ($char === ' ') {
				$result .= $char;
				continue;
			}

			if (isset($dateFormatToIntlFormatMap[$char])) {
				$formatter = $this->getIntlFormatter($locale);
				$formatter->setPattern($dateFormatToIntlFormatMap[$char]);
				$formatter->setTimeZone($timestamp->getTimezone());
				$result .=  $formatter->format($timestamp);
				continue;
			}

			$input = mb_substr($format, $i);
			if ($char === 'L' && preg_match('/^(LTS|LT|L{1,4})/', $input, $match)) {
				$code = $match[0];
				$localFormats = self::$localFormats[$locale] ?? self::$localFormats['en_GB'];
				$result .= $this->formatTimestamp($localFormats[$code], $timestamp, $locale);
				$i += mb_strlen($code) - 1;
				continue;
			}

			$result .= $timestamp->format($char);
		}
		return $result;
	}

	private function getIntlFormatter(string $locale): IntlDateFormatter
	{
		$dateType = IntlDateFormatter::FULL;
		$timeType = IntlDateFormatter::FULL;

		$key = $dateType . ':' . $timeType . ':' . $locale;
		if (!isset(self::$intlCache[$key])) {
			self::$intlCache[$key] = new IntlDateFormatter($locale, $dateType, $timeType);
		}

		return self::$intlCache[$key];
	}
}
