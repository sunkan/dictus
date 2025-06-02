<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\LocalizedStrftimeFormatter;

final class LocalizedStrftimeFormatterTest extends TestCase
{
	public function testReadableFormat(): void
	{
		$formatter = new LocalizedStrftimeFormatter('is_IS', '%A %c Test');

		$date = new DateTimeImmutable('2023-08-21 16:26:14');

		$this->assertSame('mánudagur 21. ágúst 2023 kl. 16:26 Test', $formatter->format($date));
	}

	public function testEnglishFormat(): void
	{
		$formatter = new LocalizedStrftimeFormatter('en_GB', '%A %c Test');

		$date = new DateTimeImmutable('2023-08-21 16:26:14');

		$this->assertSame('Monday 21 August 2023 at 16:26 Test', $formatter->format($date));
	}
}
