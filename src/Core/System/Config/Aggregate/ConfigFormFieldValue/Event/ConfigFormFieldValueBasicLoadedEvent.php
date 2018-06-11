<?php declare(strict_types=1);

namespace Shopware\Core\System\Config\Aggregate\ConfigFormFieldValue\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\System\Config\Aggregate\ConfigFormFieldValue\Collection\ConfigFormFieldValueBasicCollection;

class ConfigFormFieldValueBasicLoadedEvent extends NestedEvent
{
    public const NAME = 'config_form_field_value.basic.loaded';

    /**
     * @var \Shopware\Core\Framework\Context
     */
    protected $context;

    /**
     * @var \Shopware\Core\System\Config\Aggregate\ConfigFormFieldValue\Collection\ConfigFormFieldValueBasicCollection
     */
    protected $configFormFieldValues;

    public function __construct(ConfigFormFieldValueBasicCollection $configFormFieldValues, Context $context)
    {
        $this->context = $context;
        $this->configFormFieldValues = $configFormFieldValues;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getConfigFormFieldValues(): ConfigFormFieldValueBasicCollection
    {
        return $this->configFormFieldValues;
    }
}
