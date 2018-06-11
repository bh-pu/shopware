<?php declare(strict_types=1);

namespace Shopware\Core\System\Config\Aggregate\ConfigFormFieldValue\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\System\Config\Aggregate\ConfigFormFieldValue\Struct\ConfigFormFieldValueSearchResult;

class ConfigFormFieldValueSearchResultLoadedEvent extends NestedEvent
{
    public const NAME = 'config_form_field_value.search.result.loaded';

    /**
     * @var ConfigFormFieldValueSearchResult
     */
    protected $result;

    public function __construct(ConfigFormFieldValueSearchResult $result)
    {
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->result->getContext();
    }
}
