<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserCrudController extends AbstractCrudController
{

    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher,

    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_USER', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MODERATOR'];
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            TextField::new('password')->onlyOnForms(),
            TextField::new('email'),
            DateTimeField::new('createdAt'),
            BooleanField::new('isVerified'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderExpanded()
            // TextEditorField::new('description'),
        ];
    }
}
