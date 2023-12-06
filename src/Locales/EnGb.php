<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class EnGb implements LocaleFormat
{
	public function resolveFormat(string $format): ?string
	{
		return match($format) {
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'd/m/Y',
			'LL' => 'j F Y',
			'LLL' => 'j F H:i',
			'LLLL' => 'l, j F Y H:i',
			default => null,
		};
	}

	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string
	{
		return null;
	}
}
