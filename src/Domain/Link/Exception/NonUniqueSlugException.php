<?php

declare(strict_types=1);

namespace Domain\Link\Exception;

use Domain\Shared\Exception\SafeMessageException;

/**
 * class NonUniqueSlugException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class NonUniqueSlugException extends SafeMessageException
{
    protected string $messageDomain = 'link';

    public function __construct(
        string $message = 'link.exceptions.non_unique_slug',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }
}
