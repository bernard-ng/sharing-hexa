<?php

declare(strict_types=1);

namespace Application\Link\Command;

use Domain\Link\Entity\Link;

/**
 * class RegisterVisitCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RegisterLinkVisitCommand
{
    public function __construct(
        public readonly Link $link,
        public readonly ?string $ip,
        public readonly ?string $user_agent,
        public readonly ?string $referer,
        public readonly array $server
    ) {
    }
}
