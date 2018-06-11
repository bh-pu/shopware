<?php declare(strict_types=1);

namespace Shopware\Core\System\Log;

use Shopware\Core\Framework\ORM\EntityDefinition;
use Shopware\Core\Framework\ORM\EntityExtensionInterface;
use Shopware\Core\Framework\ORM\Field\DateField;
use Shopware\Core\Framework\ORM\Field\IdField;
use Shopware\Core\Framework\ORM\Field\LongTextField;
use Shopware\Core\Framework\ORM\Field\StringField;
use Shopware\Core\Framework\ORM\Field\TenantIdField;
use Shopware\Core\Framework\ORM\Field\VersionField;
use Shopware\Core\Framework\ORM\FieldCollection;
use Shopware\Core\Framework\ORM\Write\Flag\PrimaryKey;
use Shopware\Core\Framework\ORM\Write\Flag\Required;
use Shopware\Core\System\Log\Collection\LogBasicCollection;
use Shopware\Core\System\Log\Event\Log\LogDeletedEvent;
use Shopware\Core\System\Log\Event\Log\LogWrittenEvent;
use Shopware\Core\System\Log\Struct\LogBasicStruct;

class LogDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'log';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            new TenantIdField(),
            (new IdField('id', 'id'))->setFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            (new StringField('type', 'type'))->setFlags(new Required()),
            (new StringField('key', 'key'))->setFlags(new Required()),
            (new LongTextField('text', 'text'))->setFlags(new Required()),
            (new DateField('date', 'date'))->setFlags(new Required()),
            (new StringField('user', 'user'))->setFlags(new Required()),
            (new StringField('ip_address', 'ipAddress'))->setFlags(new Required()),
            (new StringField('user_agent', 'userAgent'))->setFlags(new Required()),
            (new StringField('value4', 'value4'))->setFlags(new Required()),
            new DateField('created_at', 'createdAt'),
            new DateField('updated_at', 'updatedAt'),
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return LogRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return LogBasicCollection::class;
    }

    public static function getDeletedEventClass(): string
    {
        return LogDeletedEvent::class;
    }

    public static function getWrittenEventClass(): string
    {
        return LogWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return LogBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return null;
    }
}
