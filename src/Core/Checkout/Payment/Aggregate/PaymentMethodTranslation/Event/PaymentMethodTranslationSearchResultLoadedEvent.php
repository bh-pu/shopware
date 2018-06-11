<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Payment\Aggregate\PaymentMethodTranslation\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\Checkout\Payment\Aggregate\PaymentMethodTranslation\Struct\PaymentMethodTranslationSearchResult;
use Shopware\Core\Framework\Event\NestedEvent;

class PaymentMethodTranslationSearchResultLoadedEvent extends NestedEvent
{
    public const NAME = 'payment_method_translation.search.result.loaded';

    /**
     * @var PaymentMethodTranslationSearchResult
     */
    protected $result;

    public function __construct(PaymentMethodTranslationSearchResult $result)
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
