<?php declare(strict_types=1);
// This is basic symfony

// Storefront is composed of pages
// Pagelet is reused pages.
/*
 * if you want to add data to one of those locations
 * you have to subscribe to their events
 */

namespace SwagShopFinder\Storefront\Subscriber;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Recovery\Common\Service\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use SwagShopFinder\Core\Content\ShopFinder\ShopFinderCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface
{
    // In config we defined showInStoreFront
    // We will call it from there in the implementation

    // We will define this class in services.xml
    // Then it is subscribed to the services system

    private $systemConfigService;
    private $shopFinderRepository;

    /*
     * Inside of this class here, we have an
     * constructor that is filled by the service container
     * that gets the system config service and shop finder repo
     * and getsubscribedEvents which is defined by the interface
     * we return the FooterPageletloadedEvent that listens to
     * onfooterPageletLoaded method inside the class.
     *
     * Fetch shops and add data to the page data that is then available
     * for twig?
     *
     * Last part is to fetchshop method itself to create the
     * criteria and addAssociation to the country and the entities
     * are returned from there.
     */

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $shopFinderRepository
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->shopFinderRepository = $shopFinderRepository;
    }

    public static function getSubscribedEvents()
    {
        // Returns an array of events to listen to

        return [
            FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void
    {
        // Check if we have the config or not
        if(!this->systemConfigService->get('SwagShopFnider.config.showInStorefront'))
        {
            return;
        }

        $shops = $this->fetchShops($event->getContext());

        // Extend pagelet to have our stuff.
        $event->getPagelet()->addExtension('swag_shop_finder', $shops);
    }

    private function fetchShops(Context $context): ShopFinderCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(5);

        /**
         * @var ShopFinderCollection $shopFinderCollection
         */
        $shopFinderCollection = $this->shopFinderRepository->search($criteria, $context)->getEntities();

        return $shopFinderCollection;
    }
}
