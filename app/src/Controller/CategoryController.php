<?php


namespace Accountant\Controller;

/**
 * Category controller
 *
 * @author Constantine
 *
 */
class CategoryController extends AbstractController
{
    /**
     * Index action, renders the list of all available categories
     */
    public function indexAction()
    {
        $categories = $this->repository('Accountant\Entity\Category')->findAll();

        return $this->render('category.index.html.twig', array('categories' => $categories));
    }

    /**
     * Action to render the form or add a category into the system
     */
    public function addAction()
    {
        $category = $this->app['accountant.entity.category'];

        $form = $this->app['form.factory']->create(
            $this->app['accountant.form.category'], $category
        );

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->app['orm.em']->persist($category);
            $this->app['orm.em']->flush();

            $this->flash(self::FLASH_MESSAGE_SUCCESS, $this->app->trans('categoryAdded'));

            return $this->app->redirect($this->app->path('categoryIndex'));
        }

        return $this->render('category.add.html.twig', array('form' => $form->createView()));
    }
}
