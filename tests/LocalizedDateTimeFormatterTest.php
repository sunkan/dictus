<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\DateTimeFormat;
use Sunkan\Dictus\LocalizedDateTimeFormatter;

final class LocalizedDateTimeFormatterTest extends TestCase
{
	public function testReadableFormat(): void
	{
		$formatter = new LocalizedDateTimeFormatter('is_IS', 'l d. F [kl.] H:i');

		$date = new DateTimeImmutable('2023-08-21 16:26:14');

		$this->assertSame('mánudagur 21. ágúst kl. 16:26', $formatter->format($date));
	}
}
