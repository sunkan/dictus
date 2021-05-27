<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface ClockInterface
{
	public function now(): \DateTimeImmutable;
}
