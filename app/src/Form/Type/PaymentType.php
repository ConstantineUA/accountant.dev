<?php

namespace Accountant\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DateTime;

/**
 * The form which is used to add / edit payment
 *
 * @author Constantine
 */
class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity',
                array(
                    'class' => 'Accountant\Entity\Category'
                )
            )
            ->add('amount', 'money',
                array(
                    'currency' => 'UAH'
                )
            )
            ->add('date', 'date',
                array(
                    'widget'=> 'single_text',
                    'format' => 'dd-MM-y',
                    'data' => new DateTime(),
                )
            )
            ->add('comment', 'textarea',
                array(
                    'required' => false,
                    'data' => '',
                )
            )
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'accountant_payment';
    }
}
