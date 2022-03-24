<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MovieCrudController extends AbstractCrudController
{
    
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

        return [
            \EasyCorp\Bundle\EasyAdminBundle\Field\IdField::new('id')
                ->hideOnForm(),
            \EasyCorp\Bundle\EasyAdminBundle\Field\TextField::new('title'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\TextField::new('genre'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\TextField::new('production_company'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField::new('duration'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\DateField::new('date_published'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField::new('actors'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField::new('directors')
            
        ];
    }
    

    
}
