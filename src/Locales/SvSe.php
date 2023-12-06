<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class SvSe implements LocaleFormat
{
	private const MONTHS_SHORT = [
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Mar',
		4 => 'Apr',
		5 => 'Maj',
		6 => 'Jun',
		7 => 'Jul',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Okt',
		11 => 'Nov',
		12 => 'Dec',
	];

	private const MONTHS_LONG = [
		1 => 'Januari',
		2 => 'Februari',
		3 => 'Mars',
		4 => 'April',
		5 => 'Maj',
		6 => 'Juni',
		7 => 'Juli',
		8 => 'Augusti',
		9 => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'December',
	];

	private const DAYS_SHORT = [
		1 => 'mån',
		2 => 'tis',
		3 => 'ons',
		4 => 'tor',
		5 => 'fre',
		6 => 'lör',
		7 => 'sön',
	];

	private const DAYS_LONG = [
		1 => 'Måndag',
		2 => 'Tisdag',
		3 => 'Onsdag',
		4 => 'Torsdag',
		5 => 'Fredag',
		6 => 'Lördag',
		7 => 'Söndag',
	];

	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string
	{
		return match ($char) {
			'D' => self::DAYS_SHORT[$dateTime->format('N')],
			'l' => self::DAYS_LONG[$dateTime->format('N')],
			'M' => self::MONTHS_SHORT[$dateTime->format('n')],
			'F' => self::MONTHS_LONG[$dateTime->format('n')],
			default => null,
		};
	}

	public function resolveFormat(string $format): ?string
	{
		return match($format) {
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'd.m.Y',
			'LL' => 'j F Y',
			'LLL' => 'j F [kl.] H:i',
			'LLLL' => 'l j F Y [kl.] H:i',
			default => null,
		};
	}
}
