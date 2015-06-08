<?php

namespace G4\Runner\Presenter\View;

use G4\Runner\Presenter\DataTransfer;

class Twig
{

    const EXTENSION = 'twig';

    private $data;

    private $dataTransfer;

    /**
     * @var string
     */
    private $templatesPath;

    /**
     * @var string
     */
    private $cachePath;


    public function __construct($data, DataTransfer $dataTransfer)
    {
        $this->data          = $data;
        $this->dataTransfer  = $dataTransfer;
        $this->cachePath     = realpath(PATH_CACHE);
        $this->templatesPath = realpath(PATH_APP . '/templates/' . strtolower($this->dataTransfer->getRequest()->getModule()));
        $this->registerTemplateEngine();
    }

    public function renderBody()
    {
        if (!$this->getFilesystemLoader()->exists($this->getTemplateFilename())) {
            throw new \Exception('Template does not exist: ' . $this->getTemplateFilename(), 500);
        }
        return $this->getTemplateEngine()
            ->loadTemplate($this->getTemplateFilename())
            ->render($this->data);
    }

    /**
     * @return \Twig_Loader_Filesystem
     */
    private function getFilesystemLoader()
    {
        return new \Twig_Loader_Filesystem($this->templatesPath);
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
            $this->dataTransfer->getRequest()->getResourceName(),
            $this->dataTransfer->getRequest()->getMethod()
        ])) . '.' . self::EXTENSION;
    }

    private function registerTemplateEngine()
    {
        require_once PATH_ROOT . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();
    }
}