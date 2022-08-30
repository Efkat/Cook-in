<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class RecipeCrudController extends AbstractCrudController
{
    protected array $stars = array(
            "⭐"=>1,
            "⭐⭐"=>2,
            "⭐⭐⭐"=>3,
            "⭐⭐⭐⭐"=>4,
            "⭐⭐⭐⭐⭐"=>5
    );

    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            AssociationField::new('user'),
            AssociationField::new('Tags'),
            TextEditorField::new('content'),
            ArrayField::new('ingredients'),
            IntegerField::new('preparation_time'),
            IntegerField::new('cooking_time'),
            ChoiceField::new('difficulty')->setChoices($this->stars)
        ];
    }
}
