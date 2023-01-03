<?php declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\FrozenClock;

final class FrozenClockTest extends TestCase
{
	public function testNowShouldAlwaysReturnTheSameObject(): void
	{
		$now = new \DateTimeImmutable();
		$clock = new FrozenClock($now);

		$this->assertSame($now, $clock->now());
		$this->assertSame($now, $clock->now());
	}

	public function testReplaceDateTimeObject(): void
	{
		$firstNow = new \DateTimeImmutable('2022-01-01 00:00:01');
		$clock = new FrozenClock($firstNow);

		$replaceNow = new \DateTimeImmutable('2023-01-01 00:00:01');
		$clock->replaceDateTime($replaceNow);

		$this->assertNotSame($firstNow, $clock->now());
		$this->assertSame($replaceNow, $clock->now());
	}

	public function testCreateClockFromString(): void
	{
		$date = '2022-06-01 12:43:11';
		$clock = FrozenClock::fromString($date);

		$this->assertSame($date, $clock->now()->format('Y-m-d H:i:s'));
	}
}
