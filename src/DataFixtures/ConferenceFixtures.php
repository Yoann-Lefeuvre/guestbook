<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Conference;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ConferenceFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {}
    
    public function load(ObjectManager $manager): void
    {
        $villes = ['Nantes','Paris','Londres','Madrid','Miami'];
        $inter = [0,0,1,1,1];
        $year = 2020;
        $faker = Factory::create('fr_FR');

        
        for($i=0; $i<count($villes); $i++)
        {
    
         $conf = new Conference();
         $conf->setCity($villes[$i]);
         $conf->setYear($year + $i);
         $conf->setIsInternational($inter[$i]);
         $conf->setSlug($this->slugger->slug($conf->getCity())->lower());

         $manager->persist($conf);
        }

        $manager->flush();
    }
}
