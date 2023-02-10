<?php declare(strict_types=1);

namespace Sunkan\Dictus;

use Psr\Clock\ClockInterface;

final class TestClock implements ClockInterface
{
	private \DateTimeImmutable $currentTime;

	public function __construct(
		private \DateTimeImmutable $initialTime,
	) {}

	public function reset(): self
	{
		$this->currentTime = $this->initialTime;
		return $this;
	}

	public function modify(string $modifier): self
	{
		$this->currentTime = $this->now()->modify($modifier);
		return $this;
	}

	public function now(): \DateTimeImmutable
	{
		if (!isset($this->currentTime)) {
			$this->currentTime = $this->initialTime;
		}
		return $this->currentTime;
	}
}
