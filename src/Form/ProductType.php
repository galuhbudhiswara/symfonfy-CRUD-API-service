<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'constraint' => [
                    new NotNull(),
                    new Length([
                        'max' => 100
                    ])
                ]
            ])
            ->add('title', TextType::class, [
                'constraint' => [
                    new NotNull(),
                ]
            ])
            ->add('price', IntegerType::class, [
                'constraint' => [
                    new NotNull(),
                    new GreaterThan([
                        'value' => 0
                    ])
                ]
            ])
            ->add('carts', EntityType::class, [
                'class' => Cart::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
