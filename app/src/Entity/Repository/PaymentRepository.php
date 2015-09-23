<?php

namespace Accountant\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Accountant\Entity\Category;
use DateTime;

/**
 * Custom repository for the payment entity
 *
 * @author Constantine
 *
 */
class PaymentRepository extends EntityRepository
{
    /**
     * Returns the list of payments for the given category
     * which took place between the given period
     *
     * @param int $id category id
     * @param DateTime $start
     * @param DateTime $end
     * @return array
     */
    public function getPaymentsByCategory($id, DateTime $start, DateTime $end)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM Accountant\Entity\Payment p
            JOIN p.category c
            WHERE c.id = :category AND p.date >= :start AND p.date <= :end
            ORDER BY p.date DESC'
        );

        $query->setParameters([
            'category' => (int) $id,
            'start' => $start,
            'end' => $end,
        ]);

        return $query->getResult();
    }
}
