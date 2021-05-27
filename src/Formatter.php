<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface Formatter
{
	public function format(\DateTimeInterface $date): string;
}
