<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Podcast;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class PodcastController extends FOSRestController
{

    /**
     * @return array|\Doctrine\ORM\Query|View
     */
    public function getAllPodcastsAction()
    {

        $em = $this->getDoctrine()->getManager();

        $podcasts = $em->getRepository('AppBundle:Podcast')->getAllPodcasts();

        if(empty($podcasts))
            return new View('No podcasts found', Response::HTTP_NOT_FOUND);

        return $podcasts;

    }

    /**
     * @param $id
     * @return array|\Doctrine\ORM\Query|View
     */
    public function getPodcastAction($id)
    {

        $podcast = $this->getDoctrine()->getRepository('AppBundle:Podcast')->getPodcast($id);

        if(empty($podcast))
            return new View('Podcast not found', Response::HTTP_NOT_FOUND);

        return $podcast;

    }

    /**
     * @param $id
     * @return \Doctrine\Common\Collections\ArrayCollection|View
     */
    public function getPodcastEpisodesAction($id)
    {

        $podcast = $this->getDoctrine()->getRepository('AppBundle:Podcast')->findOneBy(['id' => $id]);

        if(!$podcast instanceof Podcast)
            return new View('Podcast not found', Response::HTTP_NOT_FOUND);

        return $podcast->getEpisodes();

    }

}
