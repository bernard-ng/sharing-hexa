<?php

declare(strict_types=1);

namespace Application\Link\Handler;

use Application\Link\Command\RegisterLinkVisitCommand;
use Application\Link\Service\DeviceDetectorServiceInterface;
use Application\Link\Service\IpAddressLocatorServiceInterface;
use Application\Shared\Mapper;
use Domain\Link\Entity\LinkVisit;
use Domain\Link\Repository\LinkRepositoryInterface;
use Domain\Link\Repository\LinkVisitRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * class RegisterVisitHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class RegisterLinkVisitHandler
{
    public function __construct(
        private readonly LinkRepositoryInterface $linkRepository,
        private readonly LinkVisitRepositoryInterface $linkVisitRepository,
        private readonly IpAddressLocatorServiceInterface $ipLocationService,
        private readonly DeviceDetectorServiceInterface $detectorService,
    ) {
    }

    public function __invoke(RegisterLinkVisitCommand $command): void
    {
        $command->link->incrementTotalVisitCount();
        if ($this->linkVisitRepository->hasAlreadyBeenVisited((string) $command->ip, $command->link) === false) {
            $visit = Mapper::getHydratedObject($command, new LinkVisit());

            $visit
                ->setLocation($this->ipLocationService->getLocation((string) $command->ip))
                ->setDevice($this->detectorService->getDevice(
                    (string) $command->user_agent,
                    $command->server
                ));

            $this->linkVisitRepository->save($visit);
            $command->link->incrementUniqueVisitCount();
        }

        $this->linkRepository->save($command->link);
    }
}
