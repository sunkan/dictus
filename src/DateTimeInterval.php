<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateTimeInterval extends \DateInterval
{
	public function __construct(string $duration, bool $recalculate = true)
	{
		parent::__construct($duration);
		if ($recalculate) {
			$this->recalculate();
		}
	}

	private function toSeconds(): int
	{
		/* Keep in mind that a year is seen in this class as 365 days, and a month is seen as 30 days.
		   It is not possible to calculate how many days are in a given year or month without a point of
		   reference in time.*/
		return ($this->y * 365 * 24 * 60 * 60) +
			($this->m * 30 * 24 * 60 * 60) +
			($this->d * 24 * 60 * 60) +
			($this->h * 60 * 60) +
			($this->i * 60) +
			$this->s;
	}

	private function recalculate(): void
	{
		$seconds = $this->toSeconds();
		$this->y = (int)floor($seconds/60/60/24/365);
		$seconds -= $this->y * 31536000;
		$this->m = (int)floor($seconds/60/60/24/30);
		$seconds -= $this->m * 2592000;
		$this->d = (int)floor($seconds/60/60/24);
		$seconds -= $this->d * 86400;
		$this->h = (int)floor($seconds/60/60);
		$seconds -= $this->h * 3600;
		$this->i = (int)floor($seconds/60);
		$seconds -= $this->i * 60;
		$this->s = $seconds;
	}
}
