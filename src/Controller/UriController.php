<?php

namespace App\Controller;

use App\Factory\PutUriRequestFactory;
use App\Service\BasicAuthentication;
use App\Service\UriManager;
use App\Struct\GetUriRequest;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UriController extends AbstractController
{
    /**
     * @Route("/{short_code}", methods={"GET"})
     * @param Request $request
     * @param UriManager $manager
     *
     * @return RedirectResponse
     */
    public function getUri(Request $request, UriManager $manager): RedirectResponse
    {
        $response = new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);

        if ($shortCode = $request->attributes->get('short_code')) {
            try {
                $uri = $manager->getUri(
                    new GetUriRequest($shortCode)
                );

                if (null !== $uri) {
                    $response->setTargetUrl($uri->getOriginalUrl());
                }
            } catch (NonUniqueResultException $exception) {
            }
        }

        return $response;
    }

    /**
     * @Route("/", methods={"PUT"})
     *
     * @param Request $request
     * @param UriManager $manager
     *
     * @return Response
     */
    public function putUri(Request $request, UriManager $manager, BasicAuthentication $authentication)
    {
        $token = $request->headers->get('authorization', '');
        if (false === $authentication->validateTokenAuthentication($token)) {
            return $this->createBadRequestResponse();
        }

        try {
            $purUriRequest = (new PutUriRequestFactory)->fromDirtyRequestContent($request);
            $uriEntity     = $manager->putUri($purUriRequest);
            $statusText    = $uriEntity->getShortCode();
        } catch (Exception $exception) {
            return $this->createBadRequestResponse();
        }

        return new Response(
            $statusText,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/", methods={"GET","HEAD","POST","DELETE","OPTIONS","PATCH","CONNECT","PURGE","TRACE"})
     */
    public function index(): Response
    {
        return new Response(
            Response::$statusTexts[Response::HTTP_I_AM_A_TEAPOT],
            Response::HTTP_I_AM_A_TEAPOT
        );
    }

    /**
     * Create a bad request response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function createBadRequestResponse(): Response
    {
        return new Response(
            Response::$statusTexts[Response::HTTP_BAD_REQUEST],
            Response::HTTP_BAD_REQUEST
        );
    }
}
