<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class LocalizedDateTimeFormatter implements LocalizedFormatter, MutableFormatter
{
	private const LOCALIZED_SHORT_FORMATS = [
		// Sorting this list correctly is important because of how we replace from it
		'LTS',
		'LT',
		'LLLL',
		'llll',
		'LLL',
		'lll',
		'LL',
		'll',
		'L',
	];
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
		return $this->formatTimestamp($this->format, \DateTimeImmutable::createFromInterface($date));
	}

	public function formatTimestamp(string $format, \DateTimeImmutable $timestamp): string
	{
		foreach (self::LOCALIZED_SHORT_FORMATS as $localizedFormat) {
			if (!str_contains($format, $localizedFormat)) {
				continue;
			}
			$tmpFormat = $this->localeFormat->resolveFormat($localizedFormat);
			if ($tmpFormat !== null) {
				$format = str_replace($localizedFormat, $tmpFormat, $format);
			}
		}

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
			if ($localResult) {
				$result .= $localResult;
				continue;
			}

			$result .= $timestamp->format($char);
		}
		return $result;
	}
}
