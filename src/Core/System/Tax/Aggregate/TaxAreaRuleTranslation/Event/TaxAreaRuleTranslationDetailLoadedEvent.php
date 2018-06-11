<?php declare(strict_types=1);

namespace Shopware\Core\System\Tax\Aggregate\TaxAreaRuleTranslation\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\System\Language\Event\LanguageBasicLoadedEvent;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;
use Shopware\Core\System\Tax\Aggregate\TaxAreaRule\Event\TaxAreaRuleBasicLoadedEvent;
use Shopware\Core\System\Tax\Aggregate\TaxAreaRuleTranslation\Collection\TaxAreaRuleTranslationDetailCollection;

class TaxAreaRuleTranslationDetailLoadedEvent extends NestedEvent
{
    public const NAME = 'tax_area_rule_translation.detail.loaded';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var \Shopware\Core\System\Tax\Aggregate\TaxAreaRuleTranslation\Collection\TaxAreaRuleTranslationDetailCollection
     */
    protected $taxAreaRuleTranslations;

    public function __construct(TaxAreaRuleTranslationDetailCollection $taxAreaRuleTranslations, Context $context)
    {
        $this->context = $context;
        $this->taxAreaRuleTranslations = $taxAreaRuleTranslations;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getTaxAreaRuleTranslations(): TaxAreaRuleTranslationDetailCollection
    {
        return $this->taxAreaRuleTranslations;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->taxAreaRuleTranslations->getTaxAreaRules()->count() > 0) {
            $events[] = new TaxAreaRuleBasicLoadedEvent($this->taxAreaRuleTranslations->getTaxAreaRules(), $this->context);
        }
        if ($this->taxAreaRuleTranslations->getLanguages()->count() > 0) {
            $events[] = new LanguageBasicLoadedEvent($this->taxAreaRuleTranslations->getLanguages(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}
