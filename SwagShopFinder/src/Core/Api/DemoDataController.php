<?php declare(strict_types=1);

use Faker\Factory;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Country\Exception\CountryNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
 * Use RouteScope to create the scope for the api route
 *
 * Give those country and shopfinder repos to the class
 *
 *
 * Make the getActiveCountry method so that it returns
 * what we wafrom the b using the filter.
 *
 * Then make the generate, write the route.
 * Now the controller can be found by the Shopware system.
 *
 * Then create the routes config file. routes.xml
 *
 * The import php annotation in xml import tag,
 * If there are the file called *Controller.php and @Route inside it,
 * This will register the routes inside the service container so that
 * they are now accessible.
 *
 * In the generate method, we will generate some fake data.
 *
 */


/**
 * @RouteScope(scopes={"api")
 */
class DemoDataController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $shopfinderRepository;

    // Since we are working with Symfony
    // this will be injected by container?

    public function __construct(EntityRepositoryInterface $countryRepository, EntityRepositoryInterface $shopfinderRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->$shopfinderRepository = $shopfinderRepository;
    }

    /**
     * @Route("/api/v{version}/_action/swag-shop-finder/generate", name="api.custom.swag_shop_finder.generate", methods={POST})
     * @return Response
     * @param Context $context
     * @throws CountryNotFoundException
     * @throws InconsistentCriteriaIdsException
     */
    public function generate(Context $context): Response
    {
        $faker = Factory::create();
        // Generates random stuff for db etc. As an initiator.
        $country = $this->getActiveCountry($context);

        $data = [];
        for ($i = 0 ; $i < 50 ; $i++) {
            $data[] = [
                'id' => Uuid::randomHex(),
                'active' => true,
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'postCode' => $faker->postcode,
                'city' => $faker->city,
                'countryId' => country->getId()
            ];
        }
        // We have an array with 50 sets.
        // We are not creating a ShopFinder ORM or Shopware DAL
        // We are not creating an object of type ShopWareFinder Entity
        // We are creating an array that holds the data
        // This is currently not an ORM

        // This creates the ORM objects from the data array and
        // pushes them to the db as I understand.
        $this->shopfinderRepository->create($data, $context);

        // This is how you interact with DBs with controllers
        // Through repositories

        // For a controller to be used by Shopware
        // create an entry in services xml

        // In the services xml, we to Dependency Injection of repositories and I guess register as a new service.

        // This is a symfony response, not shopware
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Context $context
     * @return CountryEntity
     * @throws CountryNotFoundException
     * @throws InconsistentCriteriaIdsException
     */
    private function getActiveCountry(Context $context): CountryEntity
    {
        // Search in DB for the first active country
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(1);

        $country = $this->countryRepository->search($criteria, $context)->getEntities()->first();
        if (country === null) {
            throw new CountryNotFoundException('');
        }

        return $country;
    }

    // After all of these, we create the config xml
    /*
     * After the creation of config xml.
     * If you want to display data from your
     * custom entity to storefront
     *
     * you need to listen to an event and put your
     * content into this event.
     *
     * Create a folder storefront/subscriber/footersubscriber
     *
     */

}
