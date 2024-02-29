<?php

namespace G4\Runner\Presenter\View\Twig;

use G4\Runner\Presenter\DataTransfer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Template
{

    /**
     * @var string
     */
    private $templatesPath;

    /**
     *
     * @var Environment
     */
    private $templateEngine;

    /**
     * @var string
     */
    private $templatesRootPath;

    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct($templatesPath, $templatesRootPath)
    {
        $this->templatesPath     = $templatesPath;
        $this->templatesRootPath = $templatesRootPath;
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function render($data, $templateName)
    {
        if (!$this->getFilesystemLoader()->exists($templateName)) {
            throw new \Exception('Twig template does not exist: ' . $templateName, 500);
        }

        return $this->getTemplateEngine()
            ->render($templateName, $data);
    }

    /**
     * @return FilesystemLoader
     */
    private function getFilesystemLoader()
    {
        return new FilesystemLoader([$this->templatesPath, $this->templatesRootPath]);
    }

    /**
     * @return Environment
     */
    private function getTemplateEngine()
    {
        if(!$this->templateEngine instanceof Environment) {
            if(is_callable(['\App\DI', 'templateEngine'])) {
                $this->templateEngine = \App\DI::templateEngine();
                if(!$this->templateEngine instanceof Environment) {
                    throw new \Exception("Template engine class is invalid");
                }
            } else {
                $this->templateEngine = new Environment($this->getFilesystemLoader(), [
                    'cache'       => realpath(PATH_CACHE),
                    'auto_reload' => true,
                    'debug'       => true,
                ]);
            }
        }

        return $this->templateEngine;
    }
}