<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class IsIs implements LocaleFormat
{
	private const DAYS_SHORT = [
		1 => 'mán',
		2 => 'þri',
		3 => 'mið',
		4 => 'fim',
		5 => 'fös',
		6 => 'lau',
		7 => 'sun',
	];

	private const DAYS_LONG = [
		1 => 'mánudagur',
		2 => 'þriðjudagur',
		3 => 'miðvikudagur',
		4 => 'fimmtudagur',
		5 => 'föstudagur',
		6 => 'laugardagur',
		7 => 'sunnudagur',
	];

	private const MONTHS_SHORT = [
		1 => 'jan',
		2 => 'feb',
		3 => 'mar',
		4 => 'apr',
		5 => 'maí',
		6 => 'jún',
		7 => 'júl',
		8 => 'ágú',
		9 => 'sep',
		10 => 'okt',
		11 => 'nóv',
		12 => 'des',
	];

	private const MONTHS_LONG = [
		1 => 'janúar',
		2 => 'febrúar',
		3 => 'mars',
		4 => 'apríl',
		5 => 'maí',
		6 => 'júní',
		7 => 'júlí',
		8 => 'ágúst',
		9 => 'september',
		10 => 'október',
		11 => 'nóvember',
		12 => 'desember',
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
			'LL' => 'j. F Y',
			'LLL' => 'j. F [kl.] H:i',
			'LLLL' => 'l j. F Y [kl.] H:i',
			default => null,
		};
	}
}
