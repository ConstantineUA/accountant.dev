<?php

namespace Accountant\Controller;

/**
 * Controller to deal with login/logout
 *
 * @author Constantine
 *
 */
class UserController extends AbstractController
{
    /**
     * Create and render login form
     */
    public function loginAction()
    {
        $form = $this->app['form.factory']->createNamedBuilder(null, 'form')
            ->setAction($this->app->path('login_check_'))
            ->add('_username', 'text')
            ->add('_password', 'password')
            ->add('Login', 'submit')
            ->getForm();

        $data = [
            'error' => $this->app['security.last_error']($this->request),
            'form' => $form->createView()
        ];

        return $this->render('login.html.twig', $data);
    }
}
