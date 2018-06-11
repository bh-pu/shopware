<?php declare(strict_types=1);

namespace Shopware\Core\System\Listing\Event;

use Shopware\Core\Framework\ORM\Write\DeletedEvent;
use Shopware\Core\Framework\ORM\Write\WrittenEvent;
use Shopware\Core\System\Listing\ListingSortingDefinition;

class ListingSortingDeletedEvent extends WrittenEvent implements DeletedEvent
{
    public const NAME = 'listing_sorting.deleted';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return ListingSortingDefinition::class;
    }
}
