<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface LocaleFormat
{
	public function resolveFormat(string $format): ?string;

	public function formatChar(string $char, \DateTimeImmutable $dateTime): ?string;
}
