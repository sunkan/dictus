<?php declare(strict_types=1);

final class DateTest extends \PHPUnit\Framework\TestCase
{
	public function testSetTimeOnDateObject(): void
	{
		$day = new \Sunkan\Dictus\Date('2024-04-25');
		$time = \Sunkan\Dictus\DateParser::fromTime('12:43');

		$date = $day->setTimeFromDateTime($time);

		$this->assertNotInstanceOf(\Sunkan\Dictus\Time::class, $date);
		$this->assertSame('2024-04-25 12:43', $date->format('Y-m-d H:i'));
	}

	public function testSetTimeFromDateTimeOnDateObject(): void
	{
		$day = new \Sunkan\Dictus\Date('2024-04-25');
		$time = new DateTimeImmutable('2021-01-05 23:12:01');

		$date = $day->setTimeFromDateTime($time);

		$this->assertNotInstanceOf(\Sunkan\Dictus\Time::class, $date);
		$this->assertSame('2024-04-25 23:12:01', $date->format('Y-m-d H:i:s'));
	}
}
