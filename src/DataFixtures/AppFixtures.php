<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\Step;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setVerified(true);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstname('User');
        $user->setLastname('User');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'user'));
        $user->setVerified(true);

        $banned = new User();
        $banned->setEmail('banned@example.com');
        $banned->setFirstname('Banned');
        $banned->setLastname('Banned');
        $banned->setRoles(['ROLE_BANNED']);
        $banned->setPassword($this->hasher->hashPassword($banned, 'banned'));
        $banned->setVerified(true);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($banned);

        $ingredients = [];
        $ingredientsList = [
            ['Farine', 'g'],
            ['Sucre', 'g'],
            ['Beurre', 'g'],
            ['Oeufs', 'unité'],
            ['Lait', 'ml'],
            ['Levure', 'g'],
            ['Chocolat', 'g'],
            ['Sel', 'g'],
            ['Huile', 'ml'],
            ['Tomates', 'g'],
            ['Boeuf', 'g'],
            ['Poulet', 'g'],
            ['Porc', 'g'],
            ['Agneau', 'g'],
            ['Saumon', 'g'],
            ['Cabillaud', 'g'],
            ['Thon', 'g'],
            ['Crevettes', 'g'],
            ['Pommes', 'g'],
            ['Poires', 'g'],
            ['Bananes', 'g'],
            ['Oranges', 'g'],
            ['Citrons', 'g'],
            ['Fraises', 'g'],
            ['Framboises', 'g'],
            ['Myrtilles', 'g'],
            ['Mûres', 'g'],
            ['Pommes de terre', 'g'],
            ['Carottes', 'g'],
            ['Poireaux', 'g'],
            ['Courgettes', 'g'],
            ['Aubergines', 'g'],
            ['Haricots verts', 'g'],
            ['Petits pois', 'g'],
            ['Champignons', 'g'],
            ['Brocolis', 'g'],
            ['Choux-fleurs', 'g'],
            ['Salade', 'g'],
            ['Concombre', 'g'],
            ['Radis', 'g'],
            ['Betteraves', 'g'],
            ['Navets', 'g'],
            ['Carottes', 'g'],
            ['Pommes de terre', 'g'],
            ['Poivrons', 'g'],
            ['Oignons', 'g'],
            ['Ail', 'g'],
            ['Pâtes', 'g'],
            ['Riz', 'g'],
            ['Crème fraîche', 'g'],
            ['Fromage', 'g'],
        ];

        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $randomIngredient = $ingredientsList[$i % count($ingredientsList)];
            $ingredient->setName($randomIngredient[0]);
            $ingredient->setQuality(mt_rand(50, 1000));
            $ingredient->setUnit($randomIngredient[1]);
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        $recipes = [];
        $difficulties = ['Facile', 'Moyen', 'Difficile'];
        $recipeNames = ['Gâteau', 'Tarte', 'Soupe', 'Salade', 'Gratin'];

        for ($i = 0; $i < 50; $i++) {
            $recipe = new Recipe();
            $recipe->setName($recipeNames[$i % count($recipeNames)]);
            $recipe->setDescription('Description détaillée de la recette');
            $recipe->setPreparationTime(mt_rand(15, 120));
            $recipe->setDifficulty($difficulties[array_rand($difficulties)]);
            $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            $recipe->addIngredient($ingredients[array_rand($ingredients)]);
            $recipe->setAuthor($i % 2 === 0 ? $admin : $user);
            $recipe->setCreatedAt(new \DateTime('-' . mt_rand(1, 365) . ' days'));

            for ($j = 0; $j < mt_rand(3, 8); $j++) {
                $step = new Step();
                $step->setDescription('Étape ' . ($j + 1) . ' de la recette ' . ($i + 1));
                $step->setOrderNumber($j + 1);
                $step->setRecipe($recipe);
                $manager->persist($step);
            }

            for ($k = 0; $k < mt_rand(0, 10); $k++) {
                $review = new Review();
                $review->setRating(mt_rand(1, 5));
                $review->setComment('Commentaire ' . ($k + 1) . ' pour la recette ' . ($i + 1));
                $review->setCreatedAt(new \DateTime('-' . mt_rand(1, 30) . ' days'));
                $review->setRecipe($recipe);
                $manager->persist($review);
            }

            $manager->persist($recipe);
            $recipes[] = $recipe;
        }

        $manager->flush();
    }
}
