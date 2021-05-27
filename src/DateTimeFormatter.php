<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateTimeFormatter implements Formatter
{
	public function __construct(
		private string $format,
	) {}

	public function format(\DateTimeInterface $date): string
	{
		return $date->format($this->format);
	}
}
