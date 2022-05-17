<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateParser
{
	public static function fromTime(string $time): \DateTimeImmutable
	{
		$colonCount = substr_count($time, ':');
		if ($colonCount === 1) {
			$dateObj = Time::createFromFormat('H:i', $time);
		}
		else {
			$dateObj = Time::createFromFormat('H:i:s', $time);
		}
		if (!$dateObj) {
			throw new \InvalidArgumentException('Invalid time. Expected "H:i" or "H:i:s"');
		}

		return $dateObj;
	}

	public static function fromDay(string $date): \DateTimeImmutable
	{
		$dateObj = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
		if (!$dateObj) {
			throw new \InvalidArgumentException('Invalid date. Expected "Y-m-d"');
		}

		return $dateObj->setTime(0, 0, 0);
	}

	public static function fromTimestamp(int $timestamp): \DateTimeImmutable
	{
		$dateObj = \DateTimeImmutable::createFromFormat('U', (string)$timestamp);
		if (!$dateObj) {
			throw new \InvalidArgumentException('Invalid date. Expected timestamp');
		}

		return $dateObj;
	}

	public static function fromString(string $date): \DateTimeImmutable
	{
		try {
			$dateObj = new \DateTimeImmutable($date);
		}
		catch (\Exception $e) {
			throw new \InvalidArgumentException('Invalid date. ' . $e->getMessage());
		}

		return $dateObj;
	}
}
