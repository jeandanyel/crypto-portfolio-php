<?php

namespace App\List;

use App\Entity\Transaction;
use Doctrine\Common\Collections\Order;
use Jeandanyel\ListBundle\Builder\ListBuilderInterface;
use Jeandanyel\ListBundle\Column\DateColumnType;
use Jeandanyel\ListBundle\Column\NumberColumnType;
use Jeandanyel\ListBundle\Column\TextColumnType;
use Jeandanyel\ListBundle\List\AbstractListType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionListType extends AbstractListType
{
    public function buildList(ListBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextColumnType::class, [
                'label' => 'Type',
                'sortable' => true,
            ])
            ->add('received_asset.cryptocurrency.name', TextColumnType::class, [
                'label' => 'Received asset',
                'sortable' => true,
                'order' => Order::Ascending->value,
            ])
            ->add('received_asset.cryptocurrency.symbol', TextColumnType::class, [
                'label' => 'Symbol',
                'sortable' => false,
            ])
            ->add('received_quantity', NumberColumnType::class, [
                'label' => 'Received quantity',
                'sortable' => true,
                'decimals' => 10,
                'trim_trailing_zeros' => true,
            ])
            ->add('date', DateColumnType::class, [
                'label' => 'Date',
                'format' => 'Y-m-d',
                'order' => Order::Descending->value,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entity_class' => Transaction::class,
            'fetch_data_from_request' => true,
        ]);
    }
}