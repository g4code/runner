<?php

namespace G4\Runner\Presenter\View;

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewAbstract;
use G4\Runner\Presenter\View\ViewInterface;
use G4\Runner\Presenter\View\Twig\Template;
use G4\Constants\Template as TemplateConst;

class Twig extends ViewAbstract implements ViewInterface
{

    /**
     * @var string
     */
    private $layoutName;

    /**
     * @var string
     */
    private $templatesPath;

    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        parent::__construct($data, $dataTransfer);
        $this->templatesPath  = realpath(PATH_APP . '/templates/' . strtolower($this->getDataTransfer()->getRequest()->getModule()));
        $this->layoutName     = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::LAYOUT);
        $this->registerTemplateEngine();
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function renderBody()
    {
        return $this->hasLayout()
            ? $this->renderContentWithLayout()
            : $this->renderOnlyContent();
    }

    private function hasLayout()
    {
        return !empty($this->layoutName);
    }

    private function renderContentWithLayout()
    {
        return (new Template($this->templatesPath))->render($this->getLayoutData(), $this->getLayoutFilename());
    }

    private function renderOnlyContent()
    {
        return (new Template($this->templatesPath))->render($this->getData(), $this->getContentFilename());
    }

    /**
     * @return string
     */
    private function getContentFilename()
    {
        $templateName = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::TEMPLATE);
        return ($templateName !== null
            ? $templateName
            : strtolower(join('/', [
                $this->getDataTransfer()->getRequest()->getResourceName(),
                $this->getDataTransfer()->getRequest()->getMethod()
            ])))
            . TemplateConst::EXTENSION_TWIG;
    }

    private function getLayoutData()
    {
        return $this->getData()
            + [TemplateConst::CONTENT => $this->renderOnlyContent()];
    }

    private function getLayoutFilename()
    {
        return $this->layoutName . TemplateConst::EXTENSION_TWIG;
    }

    private function registerTemplateEngine()
    {
        require_once PATH_ROOT . '/vendor/twig/twig/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();
    }
}