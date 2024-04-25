<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class Time extends \DateTimeImmutable
{
	public function setDateFromDateTime(\DateTimeImmutable $date): \DateTimeImmutable
	{
		return \DateTimeImmutable::createFromInterface($date->setTime(
			(int)$this->format('H'),
			(int)$this->format('i'),
			(int)$this->format('s')
		));
	}
}
