<?php declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\TestClock;

final class TestClockTest extends TestCase
{
	public function testNowShouldDefaultToReturnSameObject(): void
	{
		$now = new \DateTimeImmutable();
		$clock = new TestClock($now);

		$this->assertSame($now, $clock->now());
		$this->assertSame($now, $clock->now());
	}

	public function testModifyAndReset(): void
	{
		$initial = new \DateTimeImmutable('2022-01-01 00:00:01');
		$clock = new TestClock($initial);
		$this->assertSame($initial, $clock->now());

		$clock->modify('+1 day');
		$this->assertNotSame($initial, $clock->now());

		$this->assertSame('2022-01-02', $clock->now()->format('Y-m-d'));

		$clock->reset();

		$this->assertSame($initial, $clock->now());
	}
}
