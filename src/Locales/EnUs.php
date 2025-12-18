<?php declare(strict_types=1);

namespace Sunkan\Dictus\Locales;

use Sunkan\Dictus\LocaleFormat;

final class EnUs implements LocaleFormat
{
	public function resolveFormat(string $format): ?string
	{
		return match($format) {
			'LT' => 'g:i A',
			'LTS' => 'g:i:s A',
			'L' => 'n/j/y',
			'LL' => 'F j, Y',
			'll' => 'M j, Y',
			'LLL' => 'F j, Y [at] g:i A',
			'lll' => 'M j, Y, g:i A',
			'LLLL' => 'l, F j, Y [at] g:i A',
			'llll' => 'D, M j, Y, g:i A',
			default => null,
		};
	}

	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string
	{
		return null;
	}
}
