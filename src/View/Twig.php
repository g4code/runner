<?php

namespace G4\Runner\View;


class Twig extends \G4\Runner\View\View
{

    const EXTENSION = 'twig';

    /**
     * @var \G4\Runner\RunnerInterface
     */
    private $appRunner;

    /**
     * @var \G4\CleanCore\Application
     */
    private $application;

    /**
     * @var array
     */
    private $httpParams;

    /**
     * @var array
     */
    private $httpResponse;

    /**
     * @var string
     */
    private $templatesPath;

    /**
     * @var string
     */
    private $cachePath;

    /**
     *
     * @param \G4\Runner\RunnerInterface $appRunner
     * @param \G4\CleanCore\Application $application
     */
    public function __construct(\G4\Runner\RunnerInterface $appRunner, \G4\CleanCore\Application $application)
    {
        $this->appRunner   = $appRunner;
        $this->application = $application;

        $this->httpParams   = $this->appRunner->getHttpRequest()->getParams();
        $this->httpResponse = $this->appRunner->getHttpResponse();

        $this->registerTemplateEngine();
    }

    public function render()
    {
        if (!$this->getFilesystemLoader()->exists($this->getTemplateFilename())) {
            throw new \Exception('Template does not exist: ' . $this->getTemplateFilename(), 500);
        }

        echo $this->getTemplateEngine()
            ->loadTemplate($this->getTemplateFilename())
            ->render($this->getData());
    }

    /**
     * @param string $cachePath
     * @return \G4\Runner\View\Twig
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = realpath($cachePath);
        return $this;
    }

    /**
     * @param string $templatesPath
     * @return \G4\Runner\View\Twig
     */
    public function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = realpath($templatesPath . '/' . strtolower($this->appRunner->getApplicationModule()));
        return $this;
    }

    /**
     * @return \Twig_Loader_Filesystem
     */
    private function getFilesystemLoader()
    {
        return new \Twig_Loader_Filesystem($this->templatesPath);
    }

    /**
     * @return array
     */
    private function getData()
    {
        $viewFormatter = new \G4\Runner\View\Response\Formatter\Basic($this->appRunner, $this->application);
        return $viewFormatter->render();
    }

    /**
     * @return \Twig_Environment
     */
    private function getTemplateEngine()
    {
        return new \Twig_Environment($this->getFilesystemLoader(), [
            'cache'       => $this->cachePath,
            'auto_reload' => true,
        ]);
    }

    /**
     * @return string
     */
    private function getTemplateFilename()
    {
        return strtolower(join('/', [
            $this->application->getRequest()->getResourceName(),
            $this->application->getRequest()->getMethod()
        ])) . '.' . self::EXTENSION;
    }

    private function registerTemplateEngine()
    {
        require_once PATH_ROOT . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();
    }
}