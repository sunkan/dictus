<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use DateTimeZone;
use Psr\Clock\ClockInterface;

final class SystemClock implements ClockInterface
{
	public function __construct(
		private DateTimeZone $timezone,
	) {}

	public function now(): \DateTimeImmutable
	{
		return new \DateTimeImmutable('now', $this->timezone);
	}

	public static function fromUTC(): self
	{
		return new self(new DateTimeZone('UTC'));
	}

	public static function fromSystemTimezone(): self
	{
		return new self(new DateTimeZone(date_default_timezone_get()));
	}
}
