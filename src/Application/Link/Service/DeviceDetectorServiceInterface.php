<?php

declare(strict_types=1);

namespace Application\Link\Service;

use Domain\Link\ValueObject\Device;

/**
 * interface DeviceDetectorService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface DeviceDetectorServiceInterface
{
    public function getDevice(string $user_agent, array $server): ?Device;
}
