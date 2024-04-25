<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class Date extends \DateTimeImmutable
{
	public function setTimeFromDateTime(\DateTimeImmutable $time): \DateTimeImmutable
	{
		return \DateTimeImmutable::createFromInterface($this->setTime(
			(int)$time->format('H'),
			(int)$time->format('i'),
			(int)$time->format('s'),
		));
	}
}
