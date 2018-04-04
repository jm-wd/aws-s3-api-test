<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Podcast\Episode;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Podcast
 */
class Podcast
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    //One to Many assoc
    private $episodes;

    public function __construct()
    {

        $this->episodes = new ArrayCollection();

        $this->dateCreated = new \DateTime();

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Podcast
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Podcast
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return ArrayCollection
     */
    public function getEpisodes()
    {

        return $this->episodes;

    }

    public function addEpisode(Episode $episode)
    {

        $this->episodes->add($episode);

    }

    public function removeEpisode(Episode $episode)
    {

        $this->episodes->removeElement($episode);

    }

}

