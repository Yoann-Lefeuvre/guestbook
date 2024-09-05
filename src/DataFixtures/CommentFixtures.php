<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Platforms\DateIntervalUnit;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\String\Slugger\SluggerInterface;

class CommentFixtures extends Fixture
{
 
    
    public function load(ObjectManager $manager): void
    {
      /*
        $faker = Factory::create('fr_FR');
   
        
        for($i=0; $i<5; $i++)
        {
           
    
         $comment = new Comment();
         $comment->setConference($conference);  // Problème pour referencer l'objet conference
         $comment->setAuthor($faker->lastName);
         $comment->setText($faker->text);
         $comment->setEmail($faker->email);
         $comment->setCreatedAtValue();
       

         $manager->persist($comment);
        }

        $manager->flush();

        */
    }
}
