<?php

declare(strict_types=1);

namespace Application\Link\Command;

use Domain\Link\Entity\Link;

/**
 * class DeleteLinkCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class DeleteLinkCommand
{
    public function __construct(
        public readonly Link $_entity
    ) {
    }
}
