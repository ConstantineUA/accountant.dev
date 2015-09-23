<?php


namespace Accountant\Controller;

use Silex\Application;
use DateTime;

/**
 * Abstract controller with the shorthand methods and common fields
 * used for creation of page-specific controllers
 *
 * @author Constantine
 *
 */
class AbstractController
{
    /**
     * Used to set date in the url, fetch date from the url
     *
     * @var string
     */
    const DATE_FORMAT_URL = 'd-m-Y';

    /**
     * Key for the success event flash message
     *
     * @var string
     */
    const FLASH_MESSAGE_SUCCESS = 'success-message';

    /**
     * Key for the warning flash message
     *
     * @var string
     */
    const FLASH_MESSAGE_WARNING = 'warning-message';

    /**
     * Key for the error flash message
     *
     * @var string
     */
    const FLASH_MESSAGE_ERROR = 'error-message';

    /**
     * Reference for the application class
     *
     * @var \Accountant\Application
     */
    protected $app;

    /**
     * Reference for the current request
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Constructor, saves application and request references
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->request = $app['request'];
    }

    /**
     * Shorthand to add a flash message
     *
     * @param string $key
     * @param string $message
     */
    public function flash($key, $message)
    {
        $this->app['session']->getFlashBag()->add($key, $message);
    }

    /**
     * Shorthand to fetch a repository
     *
     * @param string $name
     */
    public function repository($name)
    {
        return $this->app['orm.em']->getRepository($name);
    }

    /**
     * Shorthand to render a twig template
     *
     * @param string $name template name
     * @param array $data template data
     */
    public function render($name, $data)
    {
        return $this->app['twig']->render($name, $data);
    }

    /**
     * Creates DateTime object from the given string with date
     *
     * @param string $date
     * @return \DateTime
     */
    public static function convertUrlDate($date)
    {
        if (!$date instanceof DateTime) {
            $date = DateTime::createFromFormat(self::DATE_FORMAT_URL, $date);
        }

        return $date;
    }

    /**
     * Converts the given string date into the date starting at midnight
     *
     * @param string $date
     * @return \DateTime
     */
    public static function convertUrlStartDate($date)
    {
        return self::convertUrlDate($date)->setTime(0, 0, 0);
    }

    /**
     * Converts the given string date into the date ending at the mignight
     *
     * @param string $date
     * @return \DateTime
     */
    public static function convertUrlEndDate($date)
    {
        return self::convertUrlDate($date)->setTime(23, 59, 59);
    }
}
