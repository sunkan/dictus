<?php declare(strict_types=1);

namespace Sunkan\Dictus;

interface DateTimeFormat
{
	public const DATABASE_DATE = 'Y-m-d';
	public const DATABASE_DATETIME = 'Y-m-d H:i:s';
	public const DATE = 'Y-m-d';
	public const TIME = 'H:i:s';
	public const DATETIME = 'Y-m-d H:i:s';

	public const JSON = 'Y-m-d\TH:i:sp';

	public const FIRST_OF_MONTH = 'Y-m-01';
	public const END_OF_MONTH = 'Y-m-t';
	public const FIRST_OF_MONTH_WITH_TIME = 'Y-m-01 00:00:00';
	public const END_OF_MONTH_WITH_TIME = 'Y-m-t 23:59:59';

	public const READABLE_DATETIME = 'j.m k\l. H:i';
	public const READABLE_TIME = 'k\l. H:i';
}
