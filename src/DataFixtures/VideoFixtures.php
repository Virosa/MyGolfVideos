<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Monolog\DateTimeImmutable;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeInterface;

class VideoFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $video = new Video();
        $video->setTitle('Megan Johnston - Out of bunker every time');
        $video->setFile(' bunker1.mp4');
        $video->setImage('MJ_OutBunker.png');
        $video->setDescription('Megan Johnson nous montre comment réussir ses sorties de bunker à chaque fois, dans le sable souple');
        $video->addCategory($this->getReference('category_Sortie de Bunker'));
        $video->setDatetime(new \DateTime());
        $slug = $this->slugger->slug($video->getTitle());
        $video->setSlug($slug);
        $manager->persist($video);

        $video = new Video();
        $video->setTitle('Neal York - Perfect chipping 1');
        $video->setFile(' chipping1.mp4');
        $video->setImage('BallsPractice.png');
        $video->setDescription('Neal York a la parfaite méthode pour le chipping réussi');
        $video->addCategory($this->getReference('category_Chipping'));
        $video->setDatetime(new \DateTime());
        $slug = $this->slugger->slug($video->getTitle());
        $video->setSlug($slug);
        $manager->persist($video);


        $video = new Video();
        $video->setTitle('Neal York - Long Putting training');
        $video->setFile('puttingLong.mp4');
        $video->setImage('BallHole.png');
        $video->setDescription('Neal York donne un exercice pour un entrainement sur plusieurs distances de putting');
        $video->addCategory($this->getReference('category_Putting'));
        $video->setDatetime(new \DateTime());
        $slug = $this->slugger->slug($video->getTitle());
        $video->setSlug($slug);
        $manager->persist($video);

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
