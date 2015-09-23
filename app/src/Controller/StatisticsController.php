<?php


namespace Accountant\Controller;

use DateTime;

class StatisticsController extends AbstractController
{
    /**
     * Index action, shows statistics for the current month
     */
    public function indexAction()
    {
        $start = new DateTime('midnight, first day of this month');
        $end = new DateTime('now');

        return $this->byCategoriesAction($start, $end);
    }

    /**
     * Action to show the total sum of expenses for each category
     * within the given period
     *
     * @param DateTime $start period start date
     * @param DateTime $end period end date
     */
    public function byCategoriesAction(DateTime $start, DateTime $end)
    {
        $data = $this->repository('Accountant\Entity\Category')->getMonthTotal($start, $end);

        $data['start'] = $start;
        $data['end'] = $end;
        $data['pageCode'] = $this->getPageCode();

        return $this->render('index.html.twig', $data);
    }

    /**
     * Action to show all payments for the given category during the incoming time period
     *
     * @param int $id category id
     * @param DateTime $start period start date
     * @param DateTime $end period end date
     */
    public function singleCategoryAction($id, DateTime $start, DateTime $end)
    {
        $data = [
            'start' => $start,
            'end' => $end,
            'category' => $this->repository('Accountant\Entity\Category')->find($id),
            'payments' => $this->repository('Accountant\Entity\Payment')->getPaymentsByCategory($id, $start, $end),
            'pageCode' => $this->getPageCode(),
        ];

        return $this->render('statistics.payments.html.twig', $data);
    }

    /**
     * Action to build the custom form to display statistics
     * and handle the given request(forms url to watch statistics)
     */
    public function customAction()
    {
        $form = $this->app['form.factory']->create(
            $this->app['accountant.form.statistics']
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $query = $form->getData();

            $name = '';
            $data = [
                'start' => $query['start']->format(self::DATE_FORMAT_URL),
                'end' => $query['end']->format(self::DATE_FORMAT_URL),
            ];

            if ($query['category']) {
                $data['id'] = $query['category']->getId();
                $name = 'statisticsByCategory';
            } else {
                $name = 'statisticsByDate';
            }

            return $this->app->redirect($this->app->path($name, $data));
        }

        $data = [
            'form' => $form->createView(),
            'pageCode' => $this->getPageCode(),
        ];

        return $this->render('statistics.custom.html.twig', $data);
    }

    /**
     * Fetches page code from cookies to highlight statistics menu item
     *
     * @return string
     */
    protected function getPageCode()
    {
        $defaultCode = 'this-month';

        return $this->request->cookies->get('pageCode', $defaultCode);
    }
}
