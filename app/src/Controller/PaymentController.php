<?php


namespace Accountant\Controller;

/**
 * Payments controller
 *
 * @author Constantine
 *
 */
class PaymentController extends AbstractController
{
    /**
     * Action to add a new payment or render the form
     */
    public function addAction()
    {
        $payment = $this->app['accountant.entity.payment'];

        $form = $this->app['form.factory']->create(
            $this->app['accountant.form.payment'], $payment
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->app['orm.em']->persist($payment);
            $this->app['orm.em']->flush($payment);

            $this->flash(self::FLASH_MESSAGE_SUCCESS, $this->app->trans('paymentAdded'));

            return $this->app->redirect($this->app->path('paymentAdd'));
        }

        return $this->render('payment.add.html.twig', array('form' => $form->createView()));
    }
}
