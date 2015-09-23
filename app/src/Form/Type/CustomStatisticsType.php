<?php

namespace Accountant\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DateTime;

/**
 * The form which is used to request a custom search
 * of payments by dates and category
 *
 * @author Constantine
 */
class CustomStatisticsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('start', 'date',
                array(
                    'widget'=> 'single_text',
                    'format' => 'dd-MM-y',
                    'data' => new DateTime('-3 month'),
                )
            )
            ->add('end', 'date',
                array(
                    'widget'=> 'single_text',
                    'format' => 'dd-MM-y',
                    'data' => new DateTime(),
                )
            )
            ->add('category', 'entity',
                array(
                    'class' => 'Accountant\Entity\Category',
                    'empty_value' => 'Category (optional)',
                    'required' => false,
                )
            )
            ->add('watch', 'submit');
    }

    public function getName()
    {
        return 'accountant_statistics';
    }
}
