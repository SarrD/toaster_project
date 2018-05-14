<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PostAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('texte')
            ->add('datePost')
            ->add('heurePost')
            ->add('visibilite')
            ->add('idUtilisateur')
            ->add('id')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('texte')
            ->add('datePost')
            ->add('heurePost')
            ->add('visibilite')
            ->add('idUtilisateur')
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
            ->add('texte')
            ->add('datePost')
            ->add('heurePost')
            ->add('visibilite')
            ->add('idUtilisateur')
            ->add('id')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('texte')
            ->add('datePost')
            ->add('heurePost')
            ->add('visibilite')
            ->add('idUtilisateur')
            ->add('id')
        ;
    }
}
