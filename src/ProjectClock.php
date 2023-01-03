<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use Psr\Clock\ClockInterface;

final class ProjectClock implements ClockInterface
{
	private static ?ClockInterface $clock = null;

	public static function setClock(ClockInterface $clock): void
	{
		self::$clock = $clock;
	}

	public static function instance(): ClockInterface
	{
		if (!self::$clock) {
			self::$clock = SystemClock::fromSystemTimezone();
		}

		return self::$clock;
	}

	public function now(): \DateTimeImmutable
	{
		if (self::$clock) {
			return self::$clock->now();
		}
		return self::instance()->now();
	}
}
