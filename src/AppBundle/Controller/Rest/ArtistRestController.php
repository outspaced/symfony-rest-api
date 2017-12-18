<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Repository\ArtistRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\ViewHandlerInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManager;

/**
 * @RouteResource("Artist")
 * @Route(service="app.controller.rest.artist")
 */
class ArtistRestController
{
    use \FOS\RestBundle\Controller\ControllerTrait;

    /**
     * @var ArtistRepository
     */
    private $artistRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    public function __construct(
        ArtistRepository $artistRepository,
        EntityManager $entityManager,
        ViewHandlerInterface $viewHandler
    ) {
        $this->artistRepository = $artistRepository;

        $this->entityManger = $entityManager;

        $this->viewHandler = $viewHandler;
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns a single Artist by GUID",
     *     @SWG\Schema(
     *         @Model(type=Artist::class)
     *     )
     * )
     * @throws NotFoundHttpException when Artist does not exist
     */
    public function getAction(Request $request, string $guid): \Symfony\Component\HttpFoundation\Response
    {
        $entities = $this->artistRepository
            ->findOneByGuid($guid);

        $view = $this->view($entities, 200);

        return $this->viewHandler->handle($view);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns all Artists",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *             @Model(type=Artist::class)
     *         )
     *     )
     * )
     * @QueryParam(name="limit", nullable=true)
     */
    public function cgetAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $entities = $this->artistRepository
            ->findBy([], [], $request->query->get('limit'));

        $view = $this->view($entities, 200);

        return $this->viewHandler->handle($view);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Creates a new Artist",
     *     @SWG\Schema(
     *         @Model(type=Artist::class)
     *     )
     * )
     */
    public function postAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $postBody = $request->getContent();

        $postData = json_decode($postBody, true);

        if (!isset($postData['name']) || empty($postData['name'])) {
            throw new BadRequestHttpException('Name was not supplied');
        }

        if (!isset($postData['guid']) || empty($postData['guid'])) {
            throw new BadRequestHttpException('GUID was not supplied');
        }

        $artist = (new Artist())
            ->setName($postData['name'])
            ->setGuid($postData['guid']);

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        $view = $this->view($artist, 200);

        return $this->viewHandler->handle($view);
    }
}
