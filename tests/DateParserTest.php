<?php declare(strict_types=1);


use Sunkan\Dictus\Date;
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
	 * @dataProvider validDates
	 */
	public function testTryUnknown(string|int|\DateTimeInterface $inputDate): void
	{
		$date = DateParser::tryUnknown($inputDate);

		$this->assertInstanceOf(\DateTimeImmutable::class, $date);
	}

	/**
	 * @dataProvider invalidDates
	 */
	public function testInvalidTryUnknown(string|int $inputDate): void
	{
		$date = DateParser::tryUnknown($inputDate);
		$this->assertNull($date);
	}

	/**
	 * @dataProvider validDates
	 */
	public function testFromUnknown(string|int|\DateTimeInterface $inputDate): void
	{
		$date = DateParser::fromUnknown($inputDate);

		$this->assertInstanceOf(\DateTimeImmutable::class, $date);
	}

	/**
	 * @dataProvider invalidDates
	 */
	public function testInvalidFromUnknown(string|int $inputDate): void
	{
		$this->expectException(\InvalidArgumentException::class);
		DateParser::fromUnknown($inputDate);
	}

	public function testMsEpoch(): void
	{
		$timestamp = 13382097973282;
		$date = DateParser::fromMsEpoch($timestamp);

		$this->assertSame('2025-01-23 09:26:13.282', $date->format('Y-m-d H:i:s.v'));
	}

	public function testMilliTimestampString(): void
	{
		$timestamp = '1660338149.654';
		$date = DateParser::fromMilliTimestamp($timestamp);

		$this->assertSame('2022-08-12 21:02:29.654', $date->format('Y-m-d H:i:s.v'));
	}

	public function testInvalidMilliTimestampString(): void
	{
		$timestamp = '166.0338149.654';

		$this->expectException(\InvalidArgumentException::class);

		DateParser::fromMilliTimestamp($timestamp);
	}

	/**
	 * @return string[][]
	 */
	public function invalidDates(): array
	{
		return [
			'float string' => ['1593053498.1000'],
			'none date' => ['random string'],
			'none numeric' => ['aaaa-bb-cc'],
		];
	}

	/**
	 * @return array<string, array{0: string|int|\DateTimeInterface}>
	 */
	public function validDates(): array
	{
		return [
			'string date' => [
				'2021-10-19 00:00:00',
			],
			'dateTime' => [
				new DateTime('2020-04-28 12:12:42'),
			],
			'dateTimeImmutable' => [
				new DateTimeImmutable('2020-04-28 12:12:42'),
			],
			'customDateClass' => [
				new Date('2020-04-28'),
			],
			'unix timestamp' => [
				1692687216,
			],
			'unix timestamp as string' => [
				'1593053498',
			]
		];
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
