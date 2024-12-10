<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Enum\TransactionType as EnumTransactionType;
use App\Form\DataTransformer\StringToAssetTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function __construct(private StringToAssetTransformer $stringToAssetTransformer)
    {   
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    EnumTransactionType::SEND->value => EnumTransactionType::SEND->value, 
                    EnumTransactionType::RECEIVE->value => EnumTransactionType::RECEIVE->value
                ]
            ])
            ->add('transacted_asset', TextType::class, ['required' => false])
            ->add('transacted_quantity', NumberType::class, ['required' => false])
            ->add('received_asset', TextType::class, ['required' => false])
            ->add('received_quantity', NumberType::class, ['required' => false])
            ->add('fee')
            ->add('date', DateTimeType::class)
            ->add('notes')
            ->add('submit', SubmitType::class)
        ;

        $builder->get('transacted_asset')->addModelTransformer($this->stringToAssetTransformer);
        $builder->get('received_asset')->addModelTransformer($this->stringToAssetTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
