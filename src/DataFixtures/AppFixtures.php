<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\TaskList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $db = $manager->getConnection();
        $db->beginTransaction();    // empêche le bug "no active transaction" lors du truncate (ddl implique auto-commit)
        $db->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $platform = $db->getDatabasePlatform();
        $db->executeStatement($platform->getTruncateTableSQL('task', true));
        $db->executeStatement($platform->getTruncateTableSQL('category', true));
        $db->executeStatement($platform->getTruncateTableSQL('task_list', true));
        $manager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        $db->commit();

        $db->beginTransaction();

        // Créer une liste de tâches
        $taskList = new TaskList();
        $taskList->setName('Liste de tâches 1');

        $categoriesName = ['Maison', 'Travail', 'Loisir'];
        $manager->persist($taskList);
        $manager->flush();

        foreach ($categoriesName as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }
        $manager->flush();

        $categories = $manager->getRepository(Category::class)->findAll();

        // 5 tâches par catégorie
        foreach ($categories as $category) {
            for ($i = 0; $i < 5; $i++) {
                $task = new Task();
                $task->setContent('Tâche ' . $category->getName() . $i);
                $task->setStatus(false);
                $task->setCategory($category);
                $task->setTaskList($taskList);
                $manager->persist($task);
            }
        }
        $manager->flush();

    }
}
