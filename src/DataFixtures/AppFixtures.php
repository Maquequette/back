<?php

namespace App\DataFixtures;

use App\Entity\Color;
use App\Entity\Tag;
use App\Entity\TagFamily;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void {

        //<editor-fold desc="User">
            // Creates "BASIC" user
            $user = new User();
            /*$user->setFirstName('user');
            $user->setLastName('api');*/
            $user->setEmail("user@api.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $manager->persist($user);

            // Creates "ADMIN" user
            $userAdmin = new User();
            /*$userAdmin->setFirstName('admin');
            $userAdmin->setLastName('api');*/
            $userAdmin->setEmail("admin@api.com");
            $userAdmin->setRoles(["ROLE_ADMIN"]);
            $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
            $manager->persist($userAdmin);
        //</editor-fold>

        //<editor-fold desc="Color">
            $colors = [];
            for ($i = 1; $i <= 5; $i++) {
                $color = new Color();
                $color->setCode('#'.$this->random_color_part().$this->random_color_part().$this->random_color_part());
                $manager->persist($color);
                $colors[] = $color;
            }
        //</editor-fold>

        //<editor-fold desc="TagFamily">
            $tagFamilies = [];
            for ($i = 1; $i <= 5; $i++) {
                $tagFamily = new TagFamily();
                $tagFamily->setLabel('TagFamily '.$i);
                $manager->persist($tagFamily);
                $tagFamilies[] = $tagFamily;
            }
        //</editor-fold>

        //<editor-fold desc="Tag">
            for ($i = 1; $i <= 10; $i++) {
                $tag = new Tag();
                $tag->setLabel('Tag '.$i);
                $tag->setColor($colors[array_rand($colors)]);
                $tag->setFamily($tagFamilies[array_rand($tagFamilies)]);
                $manager->persist($tag);
            }
        //</editor-fold>

        $manager->flush();
    }

    private function random_color_part(): string {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
}
