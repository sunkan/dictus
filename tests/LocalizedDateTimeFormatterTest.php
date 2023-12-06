<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\DateTimeFormat;
use Sunkan\Dictus\LocalizedDateTimeFormatter;

final class LocalizedDateTimeFormatterTest extends TestCase
{
	public function testReadableFormat(): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat('is_IS', new \Sunkan\Dictus\Locales\IsIs());
		$formatter = new LocalizedDateTimeFormatter('is_IS', 'l LLL \T\e\s\t');

		$date = new DateTimeImmutable('2023-08-21 16:26:14');

		$this->assertSame('mánudagur 21. ágúst kl. 16:26 Test', $formatter->format($date));
	}

	public function testEnglishFormat(): void
	{
		LocalizedDateTimeFormatter::addLocaleFormat('en_GB', new \Sunkan\Dictus\Locales\EnGb());
		$formatter = new LocalizedDateTimeFormatter('en_GB', 'l LLL \T\e\s\t');

		$date = new DateTimeImmutable('2023-08-21 16:26:14');

		$this->assertSame('Monday 21 August 16:26 Test', $formatter->format($date));
	}
}
