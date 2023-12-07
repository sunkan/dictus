<?php declare(strict_types=1);

namespace Sunkan\Dictus\Convert;

final class StrftimeToDateTimeFormat
{
	public const MAP = [
		'%a' => 'D', // short day
		'%A' => 'l', // long day
		'%d' => 'd', // two-digit day of the month (with leading zeros)
		'%e' => 'j', // day of the month, with a space preceding single digits
		'%j' => 'z', // day of the year, 3 digits with leading zeros
		'%u' => 'N', //	ISO-8601 numeric representation of the day of the week	1 (for Monday) through 7 (for Sunday)
		'%w' => 'w', // Numeric representation of the day of the week	0 (for Sunday) through 6 (for Saturday)
		'%U' => 'W', // Week number of the given year, starting with the first Sunday as the first week
		'%V' => 'W', // ISO-8601:1988 week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week	01 through 53 (where 53 accounts for an overlapping week)
		'%W' => 'W', // A numeric representation of the week of the year, starting with the first Monday as the first week
		'%b' => 'M', // Abbreviated month name, based on the locale	Jan through Dec
		'%B' => 'F', // Full month name, based on the locale	January through December
		'%h' => 'M', // Abbreviated month name, based on the locale (an alias of %b)	Jan through Dec
		'%m' => 'm', // Two digit representation of the month	01 (for January) through 12 (for December)
		'%C' => null, // Two digit representation of the century (year divided by 100, truncated to an integer)	19 for the 20th Century
		'%g' => null, // Two digit representation of the year going by ISO-8601:1988 standards (see %V)	Example: 09 for the week of January 6, 2009
		'%G' => 'o', // The full four-digit version of %g	Example: 2008 for the week of January 3, 2009
		'%y' => 'y', // Two digit representation of the year	Example: 09 for 2009, 79 for 1979
		'%Y' => 'Y', // Four digit representation for the year	Example: 2038
		'%H' => 'H', // Two digit representation of the hour in 24-hour format	00 through 23
		'%k' => 'G', // Hour in 24-hour format, with a space preceding single digits	0 through 23
		'%I' => 'h', // Two digit representation of the hour in 12-hour format	01 through 12
		'%l' => 'g', // (lower-case 'L')	Hour in 12-hour format, with a space preceding single digits	1 through 12
		'%M' => 'i', // Two digit representation of the minute	00 through 59
		'%p' => 'A', // UPPER-CASE 'AM' or 'PM' based on the given time	Example: AM for 00:31, PM for 22:23
		'%P' => 'a', // lower-case 'am' or 'pm' based on the given time	Example: am for 00:31, pm for 22:23
		'%r' => 'h:i:s A', // Same as "%I:%M:%S %p"	Example: 09:34:17 PM for 21:34:17
		'%R' => 'H:i', // Same as "%H:%M"	Example: 00:35 for 12:35 AM, 16:44 for 4:44 PM
		'%S' => 's', // Two digit representation of the second	00 through 59
		'%T' => 'H:i:s', // Same as "%H:%M:%S"	Example: 21:34:17 for 09:34:17 PM
		'%X' => 'LTS',
		'%z' => 'xx', // The time zone offset. Not implemented as described on Windows. See below for more information.
		'%Z' => 'z', // The time zone abbreviation. Not implemented as described on Windows. See below for more information.
		'%c' => 'LLL', // Preferred date and time stamp based on locale	Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
		'%D' => 'm/d/Y', // Same as "%m/%d/%y"	Example: 02/05/09 for February 5, 2009
		'%F' => 'Y-m-d', // Same as "%Y-%m-%d" (commonly used in database datestamps)	Example: 2009-02-05 for February 5, 2009
		'%x' => 'L', // Preferred date representation based on locale, without the time	Example: 02/05/09 for February 5, 2009
		'%%' => '\%',
		'%n' => '\n',
		'%t' => '\t',
	];

	public static function convert(string $format): string
	{
		$result = '';
		$length = mb_strlen($format);
		for ($i = 0; $i < $length; $i++) {
			$char = mb_substr($format, $i, 1);
			if ($char === '%') {
				$newChar = mb_substr($format, $i, 2);
				if (isset(self::MAP[$newChar])) {
					$result .= self::MAP[$newChar];
					$i += 1;
					continue;
				}
			}

			$result .= $char;
		}
		return $result;
	}
}
