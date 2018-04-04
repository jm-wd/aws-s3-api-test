<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Podcast;
use AppBundle\Entity\Podcast\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPodcastEpisodeData extends Fixture
{

    public function load(ObjectManager $manager)
    {

        //create 10 podcast with 5 episodes each
        for ($i = 1; $i <= 10; $i++) {

            $podcast = new Podcast();

            $podcast->setName('Podcast ' . $i);
            $podcast->setDateCreated(new \DateTime());

            $manager->persist($podcast);

            for($e = 1; $e <= 5; $e++) {

                $episode = new Episode();

                $episode->setTitle('Title' . $e);
                $episode->setDescription('Desc...' . $e);
                $episode->setNumber($e);
                $episode->setUrl('https://example/podcast/episode-' . $e . '.mp3');
                $episode->setDateCreated(new \DateTime());
                $episode->setDateModified(new \DateTime());

                $episode->setPodcast($podcast);

                $manager->persist($episode);

            }


        }

        $manager->flush();

    }

}