<?php declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\SystemClock;

final class SystemClockTest extends TestCase
{
	public function testNowShouldRespectTheProvidedTimezone(): void
	{
		$timezone = new \DateTimeZone('America/Sao_Paulo');
		$clock = new SystemClock($timezone);

		$lower = new \DateTimeImmutable('now', $timezone);
		$now = $clock->now();
		$upper = new \DateTimeImmutable('now', $timezone);

		$this->assertEquals($timezone, $now->getTimezone());
		$this->assertGreaterThanOrEqual($lower, $now);
		$this->assertLessThanOrEqual($upper, $now);
	}

	public function testCreateWithUtcAsTimezone(): void
	{
		$clock = SystemClock::fromUTC();
		$now = $clock->now();

		$this->assertSame('UTC', $now->getTimezone()->getName());
	}

	public function testCreateWithDefaultTimezone(): void
	{
		$clock = SystemClock::fromSystemTimezone();
		$now = $clock->now();

		$this->assertSame(date_default_timezone_get(), $now->getTimezone()->getName());
	}
}
