<?php

declare(strict_types=1);

namespace Application\Link\Handler;

use Application\Link\Command\DeleteLinkCommand;
use Domain\Link\Repository\LinkRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * class DeleteLinkHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class DeleteLinkHandler
{
    public function __construct(
        private readonly LinkRepositoryInterface $repository
    ) {
    }

    public function __invoke(DeleteLinkCommand $command): void
    {
        $this->repository->delete($command->_entity);
    }
}
