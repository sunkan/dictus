<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class EnGb implements LocaleFormat
{
	public function resolveFormat(string $format): ?string
	{
		return match ($format) {
			'LT' => 'H:i',
			'LTS' => 'H:i:s',
			'L' => 'd/m/Y',
			'LL' => 'j F Y',
			'll' => 'j M Y',
			'LLL' => 'j F Y [at] H:i',
			'lll' => 'j M Y, H:i',
			'LLLL' => 'l, j F Y [at] H:i',
			'llll' => 'D, j M Y, H:i',
			default => null,
		};
	}

	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string
	{
		if ($char === 'M' && $dateTime->format('n') === '9') {
			return 'Sept';
		}
		return null;
	}
}
