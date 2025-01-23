<?php declare(strict_types=1);

namespace Sunkan\Dictus;

final class DateParser
{
	private const MS_EPOCH = 11644473600000;

	public static function fromUnknown(string|int|\DateTimeInterface $date): \DateTimeImmutable
	{
		if ($date instanceof \DateTimeImmutable) {
			return $date;
		}
		if ($date instanceof \DateTimeInterface) {
			return \DateTimeImmutable::createFromInterface($date);
		}
		if (is_int($date) || is_numeric($date)) {
			$date = \DateTimeImmutable::createFromFormat('U', (string)$date);
			if (!$date) {
				throw new \InvalidArgumentException('Failed to parse timestamp');
			}
			return $date;
		}
		try {
			return new \DateTimeImmutable($date);
		}
		catch (\Throwable $e) {
			throw new \InvalidArgumentException('Failed to parse date', 0, $e);
		}
	}

	public static function tryUnknown(string|int|\DateTimeInterface $date): ?\DateTimeImmutable
	{
		if ($date instanceof \DateTimeImmutable) {
			return $date;
		}
		if ($date instanceof \DateTimeInterface) {
			return \DateTimeImmutable::createFromInterface($date);
		}
		if (is_int($date) || is_numeric($date)) {
			return \DateTimeImmutable::createFromFormat('U', (string)$date) ?: null;
		}
		try {
			return new \DateTimeImmutable($date);
		}
		catch (\Throwable) {
			return null;
		}
	}

	public static function fromTime(string $time): Time
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

		/** @noinspection PhpIncompatibleReturnTypeInspection - phpstorm is wrong */
		return $dateObj;
	}

	public static function fromDay(string $date): Date
	{
		$parts = explode('-', $date);
		if (count($parts) !== 3) {
			throw new \InvalidArgumentException('Invalid date. Expected "Y-m-d"');
		}
		if (strlen($parts[0]) !== 4) {
			throw new \InvalidArgumentException('Invalid date. Expected "Y-m-d"');
		}

		$dateObj = Date::createFromFormat('Y-m-d', $date);
		if (!$dateObj) {
			throw new \InvalidArgumentException('Invalid date. Expected "Y-m-d"');
		}

		/** @noinspection PhpIncompatibleReturnTypeInspection - phpstorm is wrong */
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

	public static function fromMilliTimestamp(int|string $timestamp): \DateTimeImmutable
	{
		$timestamp = (string)$timestamp;
		if (!str_contains($timestamp, '.')) {
			$timestamp = substr($timestamp, 0, -3) . '.' . substr($timestamp, -3);
		}

		$dateObj = \DateTimeImmutable::createFromFormat('U.v', $timestamp);
		if (!$dateObj) {
			throw new \InvalidArgumentException('Invalid date. Expected timestamp');
		}

		return $dateObj;
	}

	public static function fromMsEpoch(int $timestamp): \DateTimeImmutable
	{
		$milliTimestamp = $timestamp - self::MS_EPOCH;

		return self::fromMilliTimestamp($milliTimestamp);
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

	public static function tryString(?string $date): ?\DateTimeImmutable
	{
		if (!$date) {
			return null;
		}
		try {
			return new \DateTimeImmutable($date);
		}
		catch (\Exception $e) {
			return null;
		}
	}

	public static function intervalFromTime(string $time): \DateInterval
	{
		$colonCount = substr_count($time, ':');
		$sec = 0;
		if ($colonCount === 1) {
			[$hour, $min] = array_map('intval', explode(':', $time));
		}
		else {
			[$hour, $min, $sec] = array_map('intval', explode(':', $time));
		}

		try {
			return new \DateInterval(sprintf('PT%dH%dM%dS', $hour, $min, $sec));
		}
		catch (\Throwable) {
			throw new \InvalidArgumentException('Invalid format. Expected "H:i" or "H:i:s"');
		}
	}

	public static function intervalFromSeconds(int $seconds): \DateInterval
	{
		return new DateTimeInterval('PT' . $seconds . 'S');
	}
}
