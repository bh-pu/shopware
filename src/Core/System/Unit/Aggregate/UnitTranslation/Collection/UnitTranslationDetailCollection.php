<?php declare(strict_types=1);

namespace Shopware\Core\System\Unit\Aggregate\UnitTranslation\Collection;

use Shopware\Core\System\Language\Collection\LanguageBasicCollection;
use Shopware\Core\System\Unit\Aggregate\UnitTranslation\Struct\UnitTranslationDetailStruct;
use Shopware\Core\System\Unit\Collection\UnitBasicCollection;

class UnitTranslationDetailCollection extends UnitTranslationBasicCollection
{
    /**
     * @var UnitTranslationDetailStruct[]
     */
    protected $elements = [];

    public function getUnits(): UnitBasicCollection
    {
        return new UnitBasicCollection(
            $this->fmap(function (UnitTranslationDetailStruct $unitTranslation) {
                return $unitTranslation->getUnit();
            })
        );
    }

    public function getLanguages(): LanguageBasicCollection
    {
        return new LanguageBasicCollection(
            $this->fmap(function (UnitTranslationDetailStruct $unitTranslation) {
                return $unitTranslation->getLanguage();
            })
        );
    }

    protected function getExpectedClass(): string
    {
        return UnitTranslationDetailStruct::class;
    }
}
