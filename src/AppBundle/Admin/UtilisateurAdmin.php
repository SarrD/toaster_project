<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UtilisateurAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('ppPath')
            ->add('bio')
            ->add('idRole')
            ->add('id')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('ppPath')
            ->add('bio')
            ->add('idRole')
            ->add('id')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('ppPath')
            ->add('bio')
            ->add('idRole')
            ->add('id',null, array('required'=>false));
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('ppPath')
            ->add('bio')
            ->add('idRole')
            ->add('id')
        ;
    }
}
