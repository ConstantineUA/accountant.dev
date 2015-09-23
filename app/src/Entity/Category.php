<?php

namespace Accountant\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * The class represents category row from the DB
 *
 * @author Constantine
 * @Entity(repositoryClass="Accountant\Entity\Repository\CategoryRepository") @Table(name="categories")
 **/
class Category
{
    const TYPE_INCOME = 0;
    const TYPE_OUTCOME = 1;

    const NAME_LENGTH = 64;

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="integer")
    */
    protected $type;

    /**
     * @OneToMany(targetEntity="Payment", mappedBy="category")
     */
    protected $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    /**
     *
     * @return the $payments
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

 /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_INCOME, self::TYPE_OUTCOME))) {
            throw new \InvalidArgumentException('Invalid payment type');
        }

        $this->type = $type;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
