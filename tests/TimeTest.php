<?php declare(strict_types=1);

final class TimeTest extends \PHPUnit\Framework\TestCase
{
	public function testSetDateOnTimeObject(): void
	{
		$day = new \Sunkan\Dictus\Date('2024-04-25');
		$time = \Sunkan\Dictus\DateParser::fromTime('12:43');

		$date = $time->setDateFromDateTime($day);

		$this->assertNotInstanceOf(\Sunkan\Dictus\Time::class, $date);
		$this->assertSame('2024-04-25 12:43', $date->format('Y-m-d H:i'));
	}
}
