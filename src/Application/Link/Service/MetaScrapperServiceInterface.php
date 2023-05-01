<?php

declare(strict_types=1);

namespace Application\Link\Service;

use Domain\Link\ValueObject\LinkMeta;

/**
 * interface MetaScrapperService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface MetaScrapperServiceInterface
{
   public function getMeta(string $url): ?LinkMeta;
}
