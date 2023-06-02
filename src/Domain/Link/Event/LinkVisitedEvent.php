<?php

declare(strict_types=1);

namespace Domain\Link\Event;

use Domain\Link\Entity\Link;

/**
 * class LinkVisitedEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class LinkVisitedEvent
{
    public function __construct(
        public readonly ?string $ip,
        public readonly ?string $user_agent,
        public readonly ?array $server,
        public readonly Link $link,
    ){}
}
