<?php

namespace Accountant\Entity;

/**
 * The class represents payment row from the DB
 *
 * @author Constantine
 * @Entity(repositoryClass="Accountant\Entity\Repository\PaymentRepository") @Table(name="payments")
 */
class Payment
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="decimal", precision=15, scale=2)
     */
    protected $amount;

    /**
     * @Column(type="datetime")
     */
    protected $date;

    /**
     * @Column(type="string")
     */
    protected $comment = '';

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="products")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @return the $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

 /**
     * @param field_type $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

 /**
     *
     * @return the $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     *
     * @param field_type $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @return the $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     *
     * @param field_type $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     *
     * @param field_type $comment
     */
    public function setComment($comment)
    {
        if (!isset($comment)) {
            $comment = '';
        }

        $this->comment = $comment;
    }

}
