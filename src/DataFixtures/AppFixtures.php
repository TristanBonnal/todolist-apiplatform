<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Reset de la db
        $manager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('task', true));
        $connection->executeStatement($platform->getTruncateTableSQL('category', true));
        $manager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $categoriesName = ['Maison', 'Travail', 'Loisir'];

        foreach ($categoriesName as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }
        $manager->flush();

        $categories = $manager->getRepository(Category::class)->findAll();

        // Une tâche par catégorie
        foreach ($categories as $category) {
            for ($i = 0; $i < 5; $i++) {
                $task = new Task();
                $task->setContent('Tâche ' . $i);
                $task->setStatus(false);
                $task->setCategory($category);
                $manager->persist($task);
            }
        }
        $manager->flush();

    }
}
