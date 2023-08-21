<?php declare(strict_types=1);


use Sunkan\Dictus\DateIntervalFormatter;
use Sunkan\Dictus\DateParser;
use PHPUnit\Framework\TestCase;

class DateParserTest extends TestCase
{
	/**
	 * @dataProvider timeStrings
	 */
	public function testFromTime(string $rawDate, string $expected): void
	{
		$date = DateParser::fromTime($rawDate);

		$this->assertSame($expected, $date->format('Y-m-d H:i:s'));
	}

	/**
	 * @dataProvider dayStrings
	 */
	public function testFromDay(string $rawDate, string $expected): void
	{
		$date = DateParser::fromDay($rawDate);

		$this->assertSame($expected, $date->format('Y-m-d H:i:s'));
	}

	public function testFromTimestamp(): void
	{
		$time = time();
		$date = DateParser::fromTimestamp($time);

		$this->assertSame(date('c', $time), $date->format('c'));
	}

	/**
	 * @dataProvider mixedDateStrings
	 */
	public function testFromString(string $rawDate, string $expected): void
	{
		$date = DateParser::fromString($rawDate);

		$this->assertSame($expected, $date->format('Y-m-d H:i:s'));
	}

	/**
	 * @dataProvider invalidDateTimeFormat
	 */
	public function testInvalidFromString(string $rawDate): void
	{
		$this->expectException(\InvalidArgumentException::class);

		$date = DateParser::fromString($rawDate);
	}

	/**
	 * @dataProvider intervalTimeInputs
	 */
	public function testIntervalFromString(string $input, string $formattedOutput): void
	{
		$interval = DateParser::intervalFromTime($input);

		$this->assertSame($formattedOutput, DateIntervalFormatter::formatInterval($interval));
	}

	/**
	 * @dataProvider invalidDateFormat
	 */
	public function testInvalidDateFormat(string $input): void
	{
		$this->expectException(InvalidArgumentException::class);

		DateParser::fromDay($input);
	}

	/**
	 * @dataProvider invalidTimeFormat
	 */
	public function testInvalidTimeFormat(string $input): void
	{
		$this->expectException(InvalidArgumentException::class);

		DateParser::fromTime($input);
	}

	/**
	 * @dataProvider intervalInSeconds
	 */
	public function testIntervalFromSeconds(int $seconds, string $expectedFormat): void
	{
		$formatter = new DateIntervalFormatter();
		$interval = DateParser::intervalFromSeconds($seconds);
		$this->assertSame($expectedFormat, $formatter->format($interval));
		$this->assertSame($seconds, $formatter->getSeconds($interval));
	}

	/**
	 * @dataProvider mixedDateStrings
	 */
	public function testTryString(string $rawDate, string $expected): void
	{
		$date = DateParser::tryString($rawDate);

		$this->assertNotNull($date);
		$this->assertSame($expected, $date->format('Y-m-d H:i:s'));
	}

	/**
	 * @dataProvider invalidDateTimeFormat
	 */
	public function testInvalidTryString(string $rawDate): void
	{
		$date = DateParser::tryString($rawDate);

		$this->assertNull($date);
	}

	/**
	 * @return string[][]
	 */
	public function dayStrings(): array
	{
		return [
			'simple day' => [
				'2021-10-19',
				'2021-10-19 00:00:00',
			]
		];
	}

	/**
	 * @return string[][]
	 */
	public function timeStrings(): array
	{
		return [
			'simple time' => [
				'13:37',
				date('Y-m-d') . ' 13:37:00',
			],
			'time with seconds' => [
				'14:53:12',
				date('Y-m-d') . ' 14:53:12',
			],
		];
	}

	/**
	 * @return string[][]
	 */
	public function mixedDateStrings(): array
	{
		return [
			'now' => [
				'now',
				date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * @return string[][]
	 */
	public function intervalTimeInputs(): array
	{
		return [
			'simple' => [
				'03:21',
				'PT3H21M',
			],
			'seconds' => [
				'04:30:45',
				'PT4H30M45S',
			],
			'0 hours' => [
				'00:30:45',
				'PT30M45S',
			],
			'0 min' => [
				'01:00:45',
				'PT1H45S',
			],
			'only sec' => [
				'00:00:45',
				'PT45S',
			],
			'zero' => [
				'00:00',
				'PT0S',
			]
		];
	}

	/**
	 * @return string[][]
	 */
	public function invalidTimeFormat(): array
	{
		return [
			'missing minutes' => ['11'],
			'to many parts' => ['11:11:11:11'],
		];
	}

	/**
	 * @return string[][]
	 */
	public function invalidDateFormat(): array
	{
		return [
			'iceland format' => ['01-07-22'],
			'date without -' => ['20220701'],
			'none numeric' => ['aaaa-bb-cc'],
		];
	}

	/**
	 * @return string[][]
	 */
	public function invalidDateTimeFormat(): array
	{
		return [
			'none date' => ['random string'],
			'none numeric' => ['aaaa-bb-cc'],
		];
	}

	/**
	 * @return array<string, array{int, string}>
	 */
	public function intervalInSeconds(): array
	{
		return [
			'1 min' => [60, 'PT1M'],
			'1 hour 15 min 30 seconds' => [4530, 'PT1H15M30S'],
			'1 day +' => [87942, 'P1DT25M42S'],
			'1 month +' => [3541243, 'P1M10DT23H40M43S'],
		];
	}
}
