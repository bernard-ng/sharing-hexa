<?php

declare(strict_types=1);

namespace Domain\Link\Repository;

use Domain\Link\Entity\Link;
use Domain\Shared\Repository\DataRepositoryInterface;

/**
 * Interface LinkRepositoryInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface LinkRepositoryInterface extends DataRepositoryInterface
{
    public function isUniqueSlug(string $slug): bool;
}
