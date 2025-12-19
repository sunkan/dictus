<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\LocaleFormat;
use Sunkan\Dictus\Locales\EnGb;
use Sunkan\Dictus\Locales\EnUs;
use Sunkan\Dictus\Locales\IsIs;
use Sunkan\Dictus\Locales\SvSe;
use Sunkan\Dictus\LocalizedDateTimeFormatter;

/**
 * https://www.php.net/manual/en/datetime.format.php
 * https://www.php.net/manual/en/intldateformatter.setpattern.php
 * https://unicode-org.github.io/icu/userguide/format_parse/datetime/
 * https://github.com/unicode-org/cldr-json
 * https://momentjs.com/
 */
final class IntlComparisonTest extends TestCase
{
	private const INTL_FORMAT_MAP = [
		'LT' => [IntlDateFormatter::NONE, IntlDateFormatter::SHORT],
		'LTS' => [IntlDateFormatter::NONE, IntlDateFormatter::MEDIUM],
		'L' => [IntlDateFormatter::SHORT, IntlDateFormatter::NONE],
		'LL' => [IntlDateFormatter::LONG, IntlDateFormatter::NONE],
		'll' => [IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE],
		'LLL' => [IntlDateFormatter::LONG, IntlDateFormatter::SHORT],
		'lll' => [IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT],
		'LLLL' => [IntlDateFormatter::FULL, IntlDateFormatter::SHORT],
		// This is not available via intl formats without setting a pattern
		// 'llll' => [],
	];

	/**
	 * @return array<string, array{string, LocaleFormat}>
	 */
	public static function provideLocales(): array
	{
		return [
			'en' => ['en_US', new EnUs()],
			'uk' => ['en_GB', new EnGb()],
			'is' => ['is_IS', new IsIs()],
			'se' => ['sv_SE', new SvSe()],
		];
	}

	/**
	 * @return array<string, array{string, LocaleFormat, int}>
	 */
	public static function provideMonths(): array
	{
		$ret = [];
		foreach (self::provideLocales() as $key => $args) {
			for ($month = 1; $month <= 12; $month++) {
				$args[2] = $month;
				$ret["$key-$month"] = $args;
			}
		}
		return $ret;
	}

	/**
	 * @return array<string, array{string, LocaleFormat, int}>
	 */
	public static function provideWeekdays(): array
	{
		$ret = [];
		foreach (self::provideLocales() as $key => $args) {
			for ($wd = 1; $wd <= 7; $wd++) {
				$args[2] = $wd;
				$ret["$key-$wd"] = $args;
			}
		}
		return $ret;
	}

	/**
	 * @return array<string, array{string, LocaleFormat, string}>
	 */
	public static function provideFormats(): array
	{
		$ret = [];
		foreach (self::provideLocales() as $key => $args) {
			foreach (array_keys(self::INTL_FORMAT_MAP) as $k) {
				$args[2] = $k;
				$ret["$key-$k"] = $args;
			}
		}
		return $ret;
	}

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
		self::checkIcuDataVersion();
	}

	/**
	 * @dataProvider provideMonths
	 */
	public function testFullMonth(string $locale, LocaleFormat $localeFormat, int $month): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, 'F');
		$intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::SHORT, null, null, 'MMMM');

		$m = $this->pad($month);
		$date = new DateTimeImmutable("2023-$m-03 11:11:11");

		$this->assertSame(
			$intlFormatter->format($date),
			$formatter->format($date),
		);
	}

	/**
	 * @dataProvider provideMonths
	 */
	public function testAbbrMonth(string $locale, LocaleFormat $localeFormat, int $month): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, 'M');
		$intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::SHORT, null, null, 'MMM');

		$m = $this->pad($month);
		$date = new DateTimeImmutable("2023-$m-03 11:11:11");

		$this->assertSame(
			$intlFormatter->format($date),
			$formatter->format($date),
		);
	}

	/**
	 * @dataProvider provideWeekdays
	 */
	public function testFullWeekday(string $locale, LocaleFormat $localeFormat, int $weekday): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, 'l');
		$intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::SHORT, null, null, 'EEEE');

		$w = $this->pad($weekday);
		$date = new DateTimeImmutable("2025-12-$w 11:11:11");

		$this->assertSame(
			$intlFormatter->format($date),
			$formatter->format($date),
		);
	}

	/**
	 * @dataProvider provideWeekdays
	 */
	public function testAbbrWeekday(string $locale, LocaleFormat $localeFormat, int $weekday): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, 'D');
		$intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::SHORT, null, null, 'EEE');

		$w = $this->pad($weekday);
		$date = new DateTimeImmutable("2025-12-$w 11:11:11");

		$this->assertSame(
			$intlFormatter->format($date),
			$formatter->format($date),
		);
	}

	/**
	 * @dataProvider provideFormats
	 */
	public function testFormats(string $locale, LocaleFormat $localeFormat, string $format): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, $format);
		[$dateType, $timeType] = self::INTL_FORMAT_MAP[$format];

		$intlFormatter = new IntlDateFormatter(
			$locale,
			$dateType,
			$timeType,
		);

		foreach ($this->createDates() as $date) {
			$this->assertSame(
				$intlFormatter->format($date),
				$formatter->format($date),
			);
		}
	}

	/**
	 * @dataProvider provideLocales
	 */
	public function testSpecialLocalFormat(string $locale, LocaleFormat $localeFormat): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat($locale, $localeFormat);
		$formatter = new LocalizedDateTimeFormatter($locale, 'llll');

		$intlFormatter = new IntlDateFormatter(
			$locale,
			IntlDateFormatter::MEDIUM,
			IntlDateFormatter::SHORT,
		);

		$dates = $this->createDates();
		foreach ($dates as $date) {
			// This should just contain the same as "lll"
			$this->assertStringContainsString(
				(string)$intlFormatter->format($date),
				$formatter->format($date),
			);
		}
		$intlFormatter->setPattern('eee');
		foreach ($dates as $date) {
			$this->assertStringContainsString(
				(string)$intlFormatter->format($date),
				$formatter->format($date),
			);
		}
	}

	private static function checkIcuDataVersion(): void
	{
		try {
			$reflector = new \ReflectionExtension('intl');
			ob_start();
			$reflector->info();
			$output = strip_tags((string)ob_get_clean());
			preg_match('/^ICU Data version (?:=>)?(.*)$/m', $output, $matches);
			$icuDataVersion = trim($matches[1]);
		} catch (\ReflectionException) {
			$icuDataVersion = '';
		}
		if ((int)$icuDataVersion < 76) {
			self::markTestSkipped(sprintf(
				'Intl comparison tests expect ICU Data version to be at least 76. Current is %s',
				$icuDataVersion,
			));
		}
	}

	/**
	 * @return list<DateTimeImmutable>
	 */
	private function createDates(): array
	{
		$date = new DateTimeImmutable('2025-01-03 09:11:11');
		$dates = [
			$date,
			$date->add(new \DateInterval('PT12H')),
		];

		for ($i = 1; $i <= 7; $i++) {
			$dates[] = $date = $date->add(new \DateInterval('P1D'));
		}
		for ($i = 1; $i <= 12; $i++) {
			$dates[] = $date = $date->add(new \DateInterval('P1M'));
		}
		return $dates;
	}

	private function pad(int $month): string
	{
		return str_pad((string)$month, 2, '0', STR_PAD_LEFT);
	}
}
