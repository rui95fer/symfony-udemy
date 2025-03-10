<?php

namespace App\Controller\Admin;

use App\Entity\MicroPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MicroPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MicroPost::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('text'),
            AssociationField::new('user')->setCrudController(UserCrudController::class),
        ];
    }

}
