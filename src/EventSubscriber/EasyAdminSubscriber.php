<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private const DEFAULT_PASSWORD = 'Pa$$w0rd';
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents(): array
    {
        return [BeforeEntityPersistedEvent::class => ['setUserPassword']];
    }

    public function setUserPassword(BeforeEntityPersistedEvent $event): void
    {
        $user = $event->getEntityInstance();
        if (!($user instanceof User)) return;

        $user->setDisable(false);
        $user->setPassword($this->passwordEncoder->hashPassword($user, self::DEFAULT_PASSWORD));
    }
}