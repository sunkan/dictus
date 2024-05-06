<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\DateTimeInterval;

final class DateTimeIntervalTest extends TestCase
{
	public function mergeTest(): void
	{
		$startInterval = new DateTimeInterval('PT1H33M');
		$mergeInterval = new \DateInterval('P1DT87M');

		$mergedInterval = $startInterval->merge($mergeInterval);

		$this->assertSame('PT1D3H', \Sunkan\Dictus\DateIntervalFormatter::formatInterval($mergedInterval));
	}

	public function testMergedIntervalRecalculate(): void
	{
		$startInterval = new DateTimeInterval('PT1H33M');
		$mergeInterval = new DateTimeInterval('PT87M');
		$mergedInterval = $startInterval->merge($mergeInterval);

		$this->assertSame('03:00', \Sunkan\Dictus\DateIntervalFormatter::formatIntervalAsTime($mergedInterval));
	}
}
