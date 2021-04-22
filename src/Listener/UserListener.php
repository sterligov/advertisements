<?php


namespace App\Listener;


use App\Domain\Entity\User;
use App\Domain\UseCase\CryptInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

final class UserListener
{
    public function __construct(
        private CryptInterface $crypt,
    ) {
    }

    /**
     * @throws \App\Domain\Exception\PasswordHashException
     */
    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('password')) {
            $hash = $this->crypt->passwordHash($user->getPassword());
            $user->setPassword($hash);
        }
    }

    /**
     * @throws \App\Domain\Exception\PasswordHashException
     */
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        $hash = $this->crypt->passwordHash($user->getPassword());

        $user->setPassword($hash);
    }
}