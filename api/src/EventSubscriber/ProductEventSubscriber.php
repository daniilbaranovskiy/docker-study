<?php


namespace App\EventSubscriber;


use App\Events\ProductEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductEventSubscriber
 * @package App\EventSubscriber
 */
class ProductEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvent::PRODUCT_CREATE => 'onObjectCreate'
        ];
    }

    /**
     * @param ProductEvent $event
     * @return void
     */
    public function onObjectCreate(ProductEvent $event)
    {
        $test = $event->getProduct();
        dd(1);
    }
}