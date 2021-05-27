<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class LocalizedFormatter implements Formatter
{
	public function __construct(
		private string $local,
		private string $format,
	) {}

	public function format(\DateTimeInterface $date): string
	{
		$formattedDate = strftime($this->format, $date->getTimestamp());
		if ($formattedDate === false) {
			throw new \RuntimeException(sprintf('Failed to format date. Format "%s"', $this->format));
		}
		return $formattedDate;
	}
}
