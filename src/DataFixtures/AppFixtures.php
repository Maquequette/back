<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Challenge;
use App\Entity\ChallengeType;
use App\Entity\Color;
use App\Entity\Difficulty;
use App\Entity\Tag;
use App\Entity\TagFamily;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void {

        $faker = Factory::create('fr_FR');

        //<editor-fold desc="User">
            // Creates "BASIC" user
            $users = [];
            for ($i = 0; $i < 5; $i++){
                $user = new User();
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
                $user->setEmail($faker->email);
                $user->setRoles(["ROLE_USER"]);
                $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
                $manager->persist($user);
                $users[] = $user;
            }

            // Creates "ADMIN" user
            $admin = new User();
            $admin->setFirstName('admin');
            $admin->setLastName('api');
            $admin->setEmail("admin@api.com");
            $admin->setRoles(["ROLE_ADMIN"]);
            $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
            $manager->persist($admin);
        //</editor-fold>

        //<editor-fold desc="Color">
            $colors = [];
            for ($i = 1; $i <= 5; $i++) {
                $color = new Color();
                $color->setCode($faker->hexColor);
                $manager->persist($color);
                $colors[] = $color;
            }
        //</editor-fold>

        //<editor-fold desc="Category">
        $categories = [];
        $categoriesTitle = ['Web Front', 'Web Design', 'UX Design', 'Mobile'];
        foreach ($categoriesTitle as $categoryTitle){
            $category = new Category();
            $category->setLabel($categoryTitle);
            $category->setDescription($categoryTitle.' description');
            $manager->persist($category);
            $categories[] = $category;
        }
        //</editor-fold>

        //<editor-fold desc="TagFamily">
            $tagFamilies = [];
            for ($i = 1; $i <= 5; $i++) {
                $tagFamily = new TagFamily();
                $tagFamily->setLabel('TagFamily '.$i);
                $tagFamily->setCategory($categories[array_rand($categories)]);
                $manager->persist($tagFamily);
                $tagFamilies[] = $tagFamily;
            }
        //</editor-fold>

        //<editor-fold desc="Tag">
            $tags = [];
            for ($i = 1; $i <= 10; $i++) {
                $tag = new Tag();
                $tag->setLabel('Tag '.$i);
                $tag->setColor($colors[array_rand($colors)]);
                $tag->setFamily($tagFamilies[array_rand($tagFamilies)]);
                $manager->persist($tag);
                $tags[] = $tag;
            }
        //</editor-fold>

        //<editor-fold desc="Difficulty">
            $difficulties = [];
            for ($i = 1; $i <= 8; $i++) {
                $difficulty = new Difficulty();
                $difficulty->setLabel('Difficulty '.$i);
                $difficulty->setDescription('random description');
                $difficulty->setSortLevel($i);
                $difficulty->setColor($colors[array_rand($colors)]);
                $manager->persist($difficulty);
                $difficulties[] = $difficulty;
            }
        //</editor-fold>

        //<editor-fold desc="ChallengeType">
            $challengeTypes = [];
            for ($i = 1; $i <= 5; $i++){
                $challengeType = new ChallengeType();
                $challengeType->setLabel('Type de challenge '.$i);
                $challengeType->setDescription('Description');
                $challengeType->setCategory($categories[array_rand($categories)]);
                $manager->persist($challengeType);
                $challengeTypes[] = $challengeType;
            }
        //</editor-fold>

        //<editor-fold desc="Challenge">
            $challenges = [];
            for ($i = 1; $i <= 15; $i++){
                $challenge = new Challenge();
                $challenge->setTitle($faker->realText(maxNbChars: 255));
                $challenge->setDescription($faker->realText(maxNbChars: 2000));
                $challenge->setDifficulty($difficulties[array_rand($difficulties)]);
                $challenge->setType($challengeTypes[array_rand($challengeTypes)]);
                $challenge->setAuthor($users[array_rand($users)]);
                $challenge->addTag($tags[array_rand($tags)]);
                $manager->persist($challenge);
                $challenges[] = $challenge;
            }
        //</editor-fold>

        $manager->flush();
    }
}
