<?php

declare(strict_types=1);

namespace Application\Link\Command;

use Domain\Link\Entity\Link;

final class CreateLinkVisitCommand
{
    public function __construct(
        public readonly Link $link,
        public readonly ?string $ip,
        public readonly ?string $user_agent,
        public readonly ?array $server,
    ) {
    }
}
