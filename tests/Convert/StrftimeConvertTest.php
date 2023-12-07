<?php declare(strict_types=1);

namespace Convert;

use PHPUnit\Framework\TestCase;
use Sunkan\Dictus\Convert\StrftimeToDateTimeFormat;

final class StrftimeConvertTest extends TestCase
{
	/**
	 * @dataProvider formats
	 */
	public function test1(string $format, string $expected): void
	{
		$newFormat = StrftimeToDateTimeFormat::convert($format);

		$this->assertSame($expected, $newFormat);
	}

	public function formats(): array
	{
		return [
			['%e. %m. %Y %R', 'j. m. Y H:i'],
			['%R %d. %B %Y', 'H:i d. F Y'],
			['%c %X', 'LLL LTS'],
			['[kl.] %R', '[kl.] H:i'],
			['[kl.] %% %n %t', '[kl.] \% \n \t'],
		];
	}
}
