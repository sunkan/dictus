<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class SystemClock implements ClockInterface
{
	public function now(): \DateTimeImmutable
	{
		return new \DateTimeImmutable('now');
	}
}
