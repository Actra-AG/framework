<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\datacheck\validatorTypes;

class IpValidator
{
	public static function validate(string $input, IpTypeEnum $ipType): bool
	{
		$filterFlags = match ($ipType) {
			IpTypeEnum::ipv4 => FILTER_FLAG_IPV4,
			IpTypeEnum::ipv6 => FILTER_FLAG_IPV6,
			default => ['flags' => null]
		};

		return (filter_var(value: $input, filter: FILTER_VALIDATE_IP, options: $filterFlags) !== false);
	}
}