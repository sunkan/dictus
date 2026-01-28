<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface FormatterDateTime extends Formatter
{
	public function formatDate(string $format, \DateTimeInterface $date): string;
}
