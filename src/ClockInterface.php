<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface ClockInterface extends \StellaMaris\Clock\ClockInterface
{
	public function now(): \DateTimeImmutable;
}
