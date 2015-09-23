<?php

namespace Accountant\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The form is used to add new categories into the system
 *
 * @author Constantine
 */
class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options['data'];

        $builder
            ->add('name', 'text',
                array(
                    'constraints' => new Assert\Length(array('max' => $category::NAME_LENGTH))
                )
            )
            ->add('type', 'choice',
                array(
                    'choices' => array($category::TYPE_INCOME => 'Income', $category::TYPE_OUTCOME => 'Outcome'),
                )
            )
            ->add('save', 'submit');

    }

    public function getName()
    {
        return 'accountant_category';
    }
}
