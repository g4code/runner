<?php

namespace G4\Runner\Presenter\View;

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewAbstract;
use G4\Runner\Presenter\View\ViewInterface;

class Twig extends ViewAbstract implements ViewInterface
{

    const EXTENSION = 'twig';

    /**
     * @var string
     */
    private $templateFilename;

    /**
     * @var string
     */
    private $templatesPath;

    /**
     * @var string
     */
    private $cachePath;


    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        parent::__construct($data, $dataTransfer);
        $this->templateFilename = null;
        $this->cachePath        = realpath(PATH_CACHE);
        $this->templatesPath    = realpath(PATH_APP . '/templates/' . strtolower($this->getDataTransfer()->getRequest()->getModule()));
        $this->registerTemplateEngine();
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function renderBody()
    {
        if (!$this->getFilesystemLoader()->exists($this->getTemplateFilename())) {
            throw new \Exception('Template does not exist: ' . $this->getTemplateFilename(), 500);
        }
        return $this->getTemplateEngine()
            ->loadTemplate($this->getTemplateFilename())
            ->render($this->getData());
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
        if ($this->templateFilename === null) {
            $this->templateFilename = strtolower(join('/', [
                $this->getDataTransfer()->getRequest()->getResourceName(),
                $this->getDataTransfer()->getRequest()->getMethod()
            ])) . '.' . self::EXTENSION;
        }
        return $this->templateFilename;
    }

    private function registerTemplateEngine()
    {
        require_once PATH_ROOT . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();
    }
}