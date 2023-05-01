<?php

declare(strict_types=1);

namespace Domain\Link\Repository;

use Domain\Link\Entity\Link;
use Domain\Shared\Repository\DataRepositoryInterface;

/**
 * Interface LinkVisitRepositoryInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface LinkVisitRepositoryInterface extends DataRepositoryInterface
{
    public function hasAlreadyBeenVisited(string $ip, Link $link): bool;
}
