<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private const CATEGORIES = [
        'Sortie de Bunker',
        'Chipping',
        'Putting',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $slug = $this->slugger->slug($category->getName());
            $category->setSlug($slug);
            $manager->persist($category);
            $this->addReference('category_' . $categoryName, $category);

            $manager->flush();
        }
    }
}