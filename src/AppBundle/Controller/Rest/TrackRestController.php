<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Track;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Track")
 */
class TrackRestController extends FOSRestController
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns a single track by GUID",
     *     @SWG\Schema(
     *         @Model(type=Track::class)
     *     )
     * )
     * @param  string $guid
     * @return \FOS\RestBundle\View\View
     * @throws NotFoundHttpException when track does not exist
     */
    public function getAction($guid)
    {
        $entities = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle\Entity\Track')
            ->findOneByGuid($guid);

        $view = $this->view($entities, 200);

        $context = (new \FOS\RestBundle\Context\Context())
            ->setGroups(['Detail', 'Default']);

        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns all tracks",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *             @Model(type=Track::class)
     *         )
     *     )
     * )
     * @QueryParam(name="limit", nullable=true)
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction(Request $request)
    {
        $entities = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle\Entity\Track')
            ->findBy([], [], $request->query->get('limit'));

        $view = $this->view($entities, 200);

        $context = (new \FOS\RestBundle\Context\Context())
            ->setGroups(['List', 'Default']);

        $view->setContext($context);

        return $this->handleView($view);
    }
}
