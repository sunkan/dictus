<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateIntervalFormatter
{
	/**
	 * The date properties.
	 *
	 * @var array<string, string>
	 */
	private static array $date = ['y' => 'Y', 'm' => 'M', 'd' => 'D'];

	/**
	 * The time properties.
	 *
	 * @var array<string, string>
	 */
	private static array $time = ['h' => 'H', 'i' => 'M', 's' => 'S'];

	public function format(\DateInterval $interval): string
	{
		return self::formatInterval($interval);
	}

	public static function formatInterval(\DateInterval $interval): string
	{
		$string = 'P';

		foreach (self::$date as $property => $suffix) {
			if ($interval->{$property}) {
				$string .= $interval->{$property} . $suffix;
			}
		}

		if ($interval->h || $interval->i || $interval->s) {
			$string .= 'T';

			foreach (self::$time as $property => $suffix) {
				if ($interval->{$property}) {
					$string .= $interval->{$property} . $suffix;
				}
			}
		}
		if ($string === 'P') {
			$string = 'PT0S';
		}

		return $string;
	}

	public function formatAsTime(\DateInterval $interval): string
	{
		return self::formatIntervalAsTime($interval);
	}

	public static function formatIntervalAsTime(\DateInterval $interval): string
	{
		$hours = $interval->h;
		if ($interval->m) {
			$hours += ($interval->m * 30) * 24;
		}
		if ($interval->d) {
			$hours += $interval->d * 24;
		}

		return sprintf('%02d:%02d', $hours, $interval->i);
	}

	public function getSeconds(\DateInterval $interval): int
	{
		/* Keep in mind that a year is seen in this class as 365 days, and a month is seen as 30 days.
		   It is not possible to calculate how many days are in a given year or month without a point of
		   reference in time.*/
		return ($interval->y * 365 * 24 * 60 * 60) +
			($interval->m * 30 * 24 * 60 * 60) +
			($interval->d * 24 * 60 * 60) +
			($interval->h * 60 * 60) +
			($interval->i * 60) +
			$interval->s;
	}
}
