<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface LocalizedFormatter extends Formatter
{
	public function setLocal(string $local): void;
}
