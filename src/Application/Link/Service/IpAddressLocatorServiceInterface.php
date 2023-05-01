<?php

declare(strict_types=1);

namespace Application\Link\Service;

use Domain\Link\ValueObject\Location;

/**
 * interface IpAddressLocatorService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface IpAddressLocatorServiceInterface
{
    public function getLocation(string $ip): ?Location;
}