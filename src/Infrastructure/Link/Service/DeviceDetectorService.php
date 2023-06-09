<?php

declare(strict_types=1);

namespace Infrastructure\Link\Service;

use Application\Link\Service\DeviceDetectorServiceInterface;
use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\OperatingSystem;
use Domain\Link\ValueObject\Device;

/**
 * class DeviceDetectorService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class DeviceDetectorService implements DeviceDetectorServiceInterface
{
    public function getDevice(string $user_agent, array $server): Device
    {
        $dd = new DeviceDetector($user_agent, ClientHints::factory($server));
        $dd->parse();

        return Device::fromArray([
            'os' => (string) OperatingSystem::getOsFamily($dd->getOs('name')),
            'client' => strval(match (true) {
                $dd->isBrowser() => Browser::getBrowserFamily(strval($dd->getClient('name'))),
                default => $dd->getclient('name'),
            }),
            'device' => $dd->getDeviceName(),
            'is_bot' => $dd->isBot(),
        ]);
    }
}
