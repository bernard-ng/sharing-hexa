<?php

declare(strict_types=1);

namespace Application\Link\Handler;

use Application\Link\Command\CreateLinkVisitCommand;
use Application\Link\Service\DeviceDetectorServiceInterface;
use Application\Link\Service\IpAddressLocatorServiceInterface;
use Domain\Link\Entity\LinkVisit;
use Domain\Link\Repository\LinkRepositoryInterface;
use Domain\Link\Repository\LinkVisitRepositoryInterface;
use Infrastructure\Link\Doctrine\Repository\LinkRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * class CreateLinkVisitHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class CreateLinkVisitHandler
{
    public function __construct(
        private readonly LinkVisitRepositoryInterface $repository,
        private readonly LinkRepositoryInterface $linkRepository,
        private readonly IpAddressLocatorServiceInterface $ipAddressLocatorService,
        private readonly DeviceDetectorServiceInterface $detectorService
    ) {
    }

    public function __invoke(CreateLinkVisitCommand $command): void
    {
        $command->link->setTotalVisitCount(
            $command->link->getTotalVisitCount() + 1
        );

        if ($this->repository->hasAlreadyBeenVisited((string) $command->ip, $command->link)) {
            $visit = (new LinkVisit())
                ->setLink($command->link)
                ->setDevice($this->detectorService->getDevice($command->user_agent, $command->server))
                ->setLocation($this->ipAddressLocatorService->getLocation($command->ip))
            ;
            $command->link->setUniqueVisitCount(
                $command->link->getUniqueVisitCount() + 1
            );

            $this->repository->save($visit);
        }

        $this->linkRepository->save($command->link);
    }
}
