<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateTimeFormatter implements FormatterDateTime, MutableFormatter
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

	public function formatTimestamp(string $format, \DateTimeImmutable $timestamp): string
	{
		return $timestamp->format($format);
	}

	public function formatDate(string $format, \DateTimeInterface $date): string
	{
		return $date->format($format);
	}
}
