<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Podcast\Episode;
use AppBundle\Entity\Podcast;
use AppBundle\File\mp3DataExtract;
use AppBundle\Form\Podcast\EpisodeType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EpisodeController extends FOSRestController
{

    /**
     * @return Podcast\Episode[]|array|View
     */
    public function getAllEpisodesAction()
    {

        $episodes = $this->getDoctrine()->getRepository('AppBundle:Podcast\Episode')->findAll();

        if(empty($episodes))
            return new View('No episodes found', Response::HTTP_NOT_FOUND);

        return $episodes;

    }

    /**
     * @param $id
     * @return Episode|View
     */
    public function getEpisodeAction($id)
    {

        $episode = $this->getDoctrine()->getRepository('AppBundle:Podcast\Episode')->findOneBy(['id' => $id]);

        if(!$episode instanceof $episode)
            return new View('No episode found', Response::HTTP_NOT_FOUND);

        return $episode;

    }

    /**
     * Accepts a file with a key of uploadFile, along with id/name for adding an episode to a podcast
     * or generating a new podcast and adding the episode to it
     *
     * @param Request $request
     * @return View|\Symfony\Component\Form\FormInterface
     */
    public function postEpisodeAction(Request $request)
    {

        //process upload and data storage
        return $this->processEpisodeAddition($request);

    }

    /**
     * @param $request
     * @return View|\Symfony\Component\Form\FormInterface
     */
    private function processEpisodeAddition($request)
    {

        $episode = new Episode();

        $form = $this->createForm(EpisodeType::class, $episode, [
            'csrf_protection' => false
        ]);

        $form->submit($request->files->all());

        if($form->isValid())
        {

            $em = $this->getDoctrine()->getManager();

            $postFields = $request->request->all();

            //Check podcast exists using name or id
            $podcast = $em->getRepository('AppBundle:Podcast')->getPodcastByIdOrName($postFields);
            $podcastStatus = '';

            //new podCast
            if(!$podcast instanceof Podcast && !isset($postFields['name']))
            {
                return new View('Podcast does not exist and name not present', Response::HTTP_BAD_REQUEST);

            } elseif(!$podcast instanceof Podcast && isset($postFields['name'])) {

                $podcast = new Podcast();
                $podcast->setName($postFields['name']);
                $em->persist($podcast);
                $podcastStatus = 'Podcast added. ';

            }

            $file = $episode->getUploadFile();

            $mp3DataExtract = new mp3DataExtract($file);
            $mp3DataExtract->extractMp3Data();
            $fileDetails = $mp3DataExtract->getMp3Data();

            $episode->setTitle($fileDetails['title'][0]);
            $episode->setDescription($fileDetails['comment'][0]);
            $episode->setNumber($fileDetails['track_number'][0]);

            $mp3Storage = $this->get('app.s3storage');

            $em->persist($episode);

            $episode->setUrl($mp3Storage->uploadFile($file, $podcast->getName()));
            $episode->setDateModified(new \DateTime());
            $episode->setPodcast($podcast);

            $em->persist($episode);

            $em->flush();

            return new View($podcastStatus . 'Episode added', Response::HTTP_CREATED);

        }

        return $form;

    }

}
