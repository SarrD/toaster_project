<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MessageAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('texte')
            ->add('dateEnvoi')
            ->add('heureEnvoi')
            ->add('idEmmeteur')
            ->add('idDestinataire')
            ->add('id')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('texte')
            ->add('dateEnvoi')
            ->add('heureEnvoi')
            ->add('idEmmeteur')
            ->add('idDestinataire')
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
            ->add('dateEnvoi')
            ->add('heureEnvoi')
            ->add('idEmmeteur')
            ->add('idDestinataire')
            ->add('id',null, array('required'=>false));
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('texte')
            ->add('dateEnvoi')
            ->add('heureEnvoi')
            ->add('idEmmeteur')
            ->add('idDestinataire')
            ->add('id')
        ;
    }
}
