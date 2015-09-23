<?php

namespace Accountant\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Accountant\Entity\Category;
use DateTime;

/**
 * Custom repository for the category entity
 *
 * @author Constantine
 *
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Returns the list of categories fields + sum of payments for each category
     * along with the total sum of income and outcome payments for the given period
     *
     * @param DateTime $start
     * @param DateTime $end
     * @return array
     */
    public function getMonthTotal(DateTime $start, DateTime $end)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT
                c.id,
                c.name,
                c.type,
                SUM(p.amount) AS total
            FROM Accountant\Entity\Category c
            JOIN c.payments p
            WHERE p.date >= :start AND p.date <= :end
            GROUP BY c.id
            ORDER BY total DESC'
        );

        $query->setParameters([
            'start' => $start,
            'end' => $end,
        ]);
        $result = $query->getArrayResult();

        $income = $this->filterPaymentsByType($result, Category::TYPE_INCOME);
        $outcome = $this->filterPaymentsByType($result, Category::TYPE_OUTCOME);

        $sum = function ($rows) {
            return array_sum(array_column($rows, 'total'));
        };

        $data = [
            'categories' => $result,
            'total' => [
                'income' => $sum($income),
                'outcome' => $sum($outcome),
            ]
        ];

        return $data;
    }

    /**
     * Returns the list of all categories sorted according to the category type
     */
    public function findAll()
    {
        return $this->findBy(array(), array('type' => 'ASC'));
    }

    /**
     * For the given list of categories returns an array consisting of
     * either income or outcome depending on the requested type
     *
     * @param array $rows list of categories
     * @param int $type type of categories for filtering
     * @return array
     */
    protected function filterPaymentsByType($rows, $type)
    {
        return array_filter($rows, function ($row) use ($type) {
            return $row['type'] == $type;
        });
    }

}
