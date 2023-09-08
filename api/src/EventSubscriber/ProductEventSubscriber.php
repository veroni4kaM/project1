<?php


namespace App\EventSubscriber;

use App\Entity\Product;
use App\Events\ProductEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductEventSubscriber
 * @package App\EventSubscriber
 */
class ProductEventSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvent::PRODUCT_UPDATE => 'onPostUpdate'
        ];
    }

    public function onPostUpdate(ProductEvent $args)
    {
        $entity = $args->getProduct();

        if ($entity instanceof Product) {
            $product = $entity;

            $currentName = $product->getName();
            $newName = "1" . $currentName;
            $product->setName($newName);

            $this->entityManager->flush();
        }
    }
}