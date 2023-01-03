<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use Psr\Clock\ClockInterface;

final class FrozenClock implements ClockInterface
{
	public static function fromString(string $date): self
	{
		return new self(DateParser::fromString($date));
	}

	public function __construct(
		private \DateTimeImmutable $now,
	) {}

	public function replaceDateTime(\DateTimeImmutable $now): void
	{
		$this->now = $now;
	}

	public function now(): \DateTimeImmutable
	{
		return $this->now;
	}
}
