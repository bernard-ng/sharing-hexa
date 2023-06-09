<?php

declare(strict_types=1);

namespace Infrastructure\Link\Service;

use Application\Link\Service\IpAddressLocatorServiceInterface;
use Domain\Link\ValueObject\Location;
use GeoIp2\Database\Reader;

/**
 * class IpAddressLocatorService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class IpAddressLocatorService implements IpAddressLocatorServiceInterface
{
    public function __construct(
        private readonly string $projectDir
    ) {
    }

    public function getLocation(string $ip): ?Location
    {
       try {
           $data = (new Reader(sprintf('%s/data/geoip_city.mmdb', $this->projectDir)))->city($ip);
           return Location::fromArray([
               'country' => $data->country->name,
               'city' => $data->city->name,
               'time_zone' => $data->location->timeZone,
               'longitude' => $data->location->longitude,
               'latitude' => $data->location->latitude,
               'accuracy_radius' => $data->location->accuracyRadius,
           ]);
       } catch (\Throwable) {
          return null;
       }
    }
}
