<?php

namespace G4\Runner\Presenter\View\Twig;


class Template
{

    /**
     * @var string
     */
    private $templatesPath;


    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct($templatesPath)
    {
        $this->templatesPath = $templatesPath;
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
            ->loadTemplate($templateName)
            ->render($data);
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
            'cache'       => realpath(PATH_CACHE),
            'auto_reload' => true,
        ]);
    }
}