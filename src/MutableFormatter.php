<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface MutableFormatter
{
	public function setFormat(string $format): void;
}
