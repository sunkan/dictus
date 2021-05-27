<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class FrozenClock implements ClockInterface
{
	public static function fromString(string $date): self
	{
		return new self(DateParser::fromString($date));
	}

	public function __construct(
		private \DateTimeImmutable $now,
	) {}

	public function now(): \DateTimeImmutable
	{
		return $this->now;
	}
}
