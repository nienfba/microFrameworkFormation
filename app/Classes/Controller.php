<?php


namespace Fab\Classes;

use Twig\Environment;
use Fab\Classes\Application;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\Intl\IntlExtension;


class Controller {

    private Environment $twig;

    /** 
     * @var Applicaton $app Accès à l'application
     */
    protected $app;


    public function __construct(Application $app) {

        $this->app = $app;

        $loader = new FilesystemLoader('app/View/');
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
        $this->twig->addExtension(new IntlExtension());
    }

    /**
     * Render an HTML Response with twig
     *
     * @param string $view la vue utilisée
     * @param array $context les paramètres (var) passés à la vue
     * 
     * @return string
     * 
     */
    public function render(string $view, array $context = []):string {
        $context = ['app'=>$this->app, 'URL'=>URL , ...$context];
        return  $this->twig->render($view, $context);
    }

    /**
     * Render an Json Response
     *
     * @param mixed $params
     * 
     * @return void
     * 
     */
    public function renderJson(mixed $params)
    {
        header('Content-Type: application/json');
        echo json_encode($params);
        exit();
    }

}