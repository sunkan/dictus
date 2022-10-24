<?php declare(strict_types=1);

final class IntervalFormatTest extends \PHPUnit\Framework\TestCase
{
	public function testFormatIntervalAsTime(): void
	{
		$interval = new \DateInterval('P2DT12H24M');

		$this->assertSame('60:24', \Sunkan\Dictus\DateIntervalFormatter::formatIntervalAsTime($interval));
	}

	public function testFormatIntervalAsTimeLongerThanAMonth(): void
	{
		$interval = new \DateInterval('P2M4DT4H41M');

		$this->assertSame('1540:41', \Sunkan\Dictus\DateIntervalFormatter::formatIntervalAsTime($interval));
	}
}
