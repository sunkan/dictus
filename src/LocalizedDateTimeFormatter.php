<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class LocalizedDateTimeFormatter implements LocalizedFormatter, MutableFormatter
{
	/** @var array<string, LocaleFormat> */
	private static array $localFormats = [];

	public static function addLocaleFormat(string $locale, LocaleFormat $format): void
	{
		self::$localFormats[$locale] = $format;
	}

	private LocaleFormat $localeFormat;

	public function __construct(
		private string $locale,
		private string $format,
	) {
		$this->setLocale($this->locale);
	}

	public function setFormat(string $format): void
	{
		$this->format = $format;
	}

	public function setLocale(string $locale): void
	{
		$this->locale = $locale;
		$this->localeFormat = self::$localFormats[$this->locale] ?? throw new \BadMethodCallException('Locale not configured: ' . $this->locale);
	}

	public function format(\DateTimeInterface $date): string
	{
		return $this->formatTimestamp($this->format, \DateTimeImmutable::createFromInterface($date), $this->locale);
	}

	public function formatTimestamp(string $format, \DateTimeImmutable $timestamp, string $locale): string
	{
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
			$localResult = $this->localeFormat->formatChar($char, $timestamp);
			if ($localResult)  {
				$result .= $localResult;
				continue;
			}

			$input = mb_substr($format, $i);
			if ($char === 'L' && preg_match('/^(LTS|LT|L{1,4})/', $input, $match)) {
				$code = $match[0];
				$newFormat = $this->localeFormat->resolveFormat($code);
				if ($newFormat) {
					$result .= $this->formatTimestamp($newFormat, $timestamp, $locale);
				}
				else {
					$result .= $code;
				}
				$i += mb_strlen($code) - 1;
				continue;
			}

			$result .= $timestamp->format($char);
		}
		return $result;
	}
}
