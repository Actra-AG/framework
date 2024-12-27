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

	public static function isInWhitelist(array $whiteList, string $ipAddressToCheck): bool
	{
		foreach ($whiteList as $whitelistItem) {
			if ($ipAddressToCheck === $whitelistItem) {
				return true;
			}
			if (!str_contains(
				haystack: $whitelistItem,
				needle: '/'
			)) {
				continue;
			}
			$tmp = explode(separator: '/', string: $whitelistItem);
			$whitelistItem = $tmp[0];
			$mask = $tmp[1];
			// Sanitize IP
			$ip1 = preg_replace(
				pattern: '_(\d+\.\d+\.\d+\.\d+).*$_',
				replacement: '$1',
				subject: "$whitelistItem.0.0.0"
			);
			// Calculate range
			$ip2 = long2ip(ip: ip2long(ip: $ip1) - 1 + (1 << (32 - $mask)));
			if (
				ip2long(ip: $ip1) <= ip2long(ip: $ipAddressToCheck)
				&& ip2long(ip: $ip2) >= ip2long(ip: $ipAddressToCheck)
			) {
				return true;
			}
		}

		return false;
	}
}