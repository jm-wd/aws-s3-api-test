<?php

namespace AppBundle\Repository\Podcast;

/**
 * EpisodeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EpisodeRepository extends \Doctrine\ORM\EntityRepository
{

    public function getPodcastEpisodes($podcastId)
    {

        $qb = $this->getEntityManager()->createQuery('SELECT e FROM AppBundle\Entity\Podcast\Episode e WHERE e.podcastId = :id');
        $qb->setParameter('id', $podcastId);

        //array result makes sure only episode data sent not doctrine object with linked podcast
        $qb = $qb->getArrayResult();

        return $qb;



    }

    public function addPodcastEpisode($podcastId, $episodeData)
    {




    }

}
