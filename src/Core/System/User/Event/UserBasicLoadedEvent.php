<?php declare(strict_types=1);

namespace Shopware\Core\System\User\Event;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\System\User\Collection\UserBasicCollection;

class UserBasicLoadedEvent extends NestedEvent
{
    public const NAME = 'user.basic.loaded';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var UserBasicCollection
     */
    protected $users;

    public function __construct(UserBasicCollection $users, Context $context)
    {
        $this->context = $context;
        $this->users = $users;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getUsers(): UserBasicCollection
    {
        return $this->users;
    }
}
