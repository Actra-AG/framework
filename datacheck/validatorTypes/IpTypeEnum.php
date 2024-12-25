<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\datacheck\validatorTypes;

enum IpTypeEnum
{
	case ip;
	case ipv4;
	case ipv6;
}