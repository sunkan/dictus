<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class SvSe implements LocaleFormat
{
	private const MONTHS_SHORT = [
		1 => 'jan.',
		2 => 'feb.',
		3 => 'mars',
		4 => 'apr.',
		5 => 'maj',
		6 => 'juni',
		7 => 'juli',
		8 => 'aug.',
		9 => 'sep.',
		10 => 'okt.',
		11 => 'nov.',
		12 => 'dec.',
	];

	private const MONTHS_LONG = [
		1 => 'januari',
		2 => 'februari',
		3 => 'mars',
		4 => 'april',
		5 => 'maj',
		6 => 'juni',
		7 => 'juli',
		8 => 'augusti',
		9 => 'september',
		10 => 'oktober',
		11 => 'november',
		12 => 'december',
	];

	private const DAYS_SHORT = [
		1 => 'mån',
		2 => 'tis',
		3 => 'ons',
		4 => 'tors',
		5 => 'fre',
		6 => 'lör',
		7 => 'sön',
	];

	private const DAYS_LONG = [
		1 => 'måndag',
		2 => 'tisdag',
		3 => 'onsdag',
		4 => 'torsdag',
		5 => 'fredag',
		6 => 'lördag',
		7 => 'söndag',
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
			'L' => 'Y-m-d',
			'LL' => 'j F Y',
			'll' => 'j M Y',
			'LLL' => 'j F Y [kl.] H:i',
			'lll' => 'j M Y H:i',
			'LLLL' => 'l j F Y [kl.] H:i',
			'llll' => 'D j M Y H:i',
			default => null,
		};
	}
}
