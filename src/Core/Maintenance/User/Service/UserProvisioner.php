<?php declare(strict_types=1);

namespace Shopware\Core\Maintenance\User\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldSerializer\PasswordFieldSerializer;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Util\Random;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Maintenance\MaintenanceException;

/**
 * @deprecated tag:v6.7.0 - reason:becomes-internal
 */
#[Package('core')]
class UserProvisioner
{
    final public const USER_EMAIL_FALLBACK = 'user@example.com';

    /**
     * @internal
     */
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @param array{firstName?: string, lastName?: string, email?: string, localeId?: string, admin?: bool} $additionalData
     */
    public function provision(string $username, ?string $password = null, array $additionalData = []): string
    {
        if ($this->userExists($username)) {
            throw MaintenanceException::userAlreadyExists($username);
        }

        $minPasswordLength = $this->getAdminPasswordMinLength();

        $password = $password ?? Random::getAlphanumericString(max($minPasswordLength, 8));

        if (\strlen($password) < $minPasswordLength) {
            throw MaintenanceException::passwordTooShort($minPasswordLength);
        }

        $userPayload = [
            'id' => Uuid::randomBytes(),
            'first_name' => $additionalData['firstName'] ?? '',
            'last_name' => $additionalData['lastName'] ?? $username,
            'email' => $additionalData['email'] ?? self::USER_EMAIL_FALLBACK,
            'username' => $username,
            'password' => password_hash($password, \PASSWORD_BCRYPT),
            'locale_id' => $additionalData['localeId'] ?? $this->getLocaleOfSystemLanguage(),
            'active' => true,
            'admin' => $additionalData['admin'] ?? true,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ];

        $this->connection->insert('user', $userPayload);

        return $password;
    }

    private function userExists(string $username): bool
    {
        $builder = $this->connection->createQueryBuilder();

        return $builder->select('1')
            ->from('user')
            ->where('username = :username')
            ->setParameter('username', $username)
            ->executeQuery()
            ->rowCount() > 0;
    }

    private function getLocaleOfSystemLanguage(): string
    {
        $builder = $this->connection->createQueryBuilder();

        return (string) $builder->select('locale.id')
                ->from('language', 'language')
                ->innerJoin('language', 'locale', 'locale', 'language.locale_id = locale.id')
                ->where('language.id = :id')
                ->setParameter('id', Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM))
                ->executeQuery()
                ->fetchOne();
    }

    private function getAdminPasswordMinLength(): int
    {
        $configKey = PasswordFieldSerializer::CONFIG_MIN_LENGTH_FOR[PasswordField::FOR_ADMIN];

        $result = $this->connection->fetchOne(
            'SELECT configuration_value FROM system_config WHERE configuration_key = :configKey AND sales_channel_id is NULL;',
            [
                'configKey' => $configKey,
            ]
        );

        if ($result === false) {
            return 0;
        }

        $config = json_decode($result, true, 512, \JSON_THROW_ON_ERROR);

        return $config['_value'] ?? 0;
    }
}
