<?php


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
	 * @dataProvider intervalTimeInputs
	 */
	public function testIntervalFromString(string $input, string $formattedOutput): void
	{
		$interval = DateParser::intervalFromTime($input);

		$this->assertSame($formattedOutput, DateIntervalFormatter::formatInterval($interval));
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
		];
	}
}
