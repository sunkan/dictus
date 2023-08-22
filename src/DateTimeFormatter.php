<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateTimeFormatter implements Formatter, MutableFormatter
{
	public function __construct(
		private string $format,
	) {}

	public function format(\DateTimeInterface $date): string
	{
		return $date->format($this->format);
	}

	public function setFormat(string $format): void
	{
		$this->format = $format;
	}
}
