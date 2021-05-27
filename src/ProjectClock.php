<?php declare(strict_types=1);

namespace Sunkan\Dictus;

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
			self::$clock = new SystemClock();
		}

		return self::$clock;
	}

	public function now(): \DateTimeImmutable
	{
		return self::instance()->now();
	}
}
