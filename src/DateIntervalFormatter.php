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

		return $string;
	}
}
