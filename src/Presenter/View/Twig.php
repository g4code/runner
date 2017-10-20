<?php

namespace G4\Runner\Presenter\View;

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewAbstract;
use G4\Runner\Presenter\View\ViewInterface;
use G4\Runner\Presenter\View\Twig\Template;
use G4\Constants\Template as TemplateConst;
use G4\Constants\Http;

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
     * @var string
     */
    private $templatesRootPath;

    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        parent::__construct($data, $dataTransfer);
        $this->templatesRootPath    = realpath(PATH_APP . '/templates');
        $this->templatesPath        = realpath(PATH_APP . '/templates/' . strtolower($this->getDataTransfer()->getRequest()->getModule()));
        $this->layoutName           = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::LAYOUT);
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
        return (new Template($this->templatesPath, $this->templatesRootPath))->render($this->getLayoutData(), $this->getLayoutFilename());
    }

    private function renderOnlyContent()
    {
        return (new Template($this->templatesPath, $this->templatesRootPath))->render($this->getData(), $this->getContentFilename());
    }

    /**
     * @return string
     */
    private function getContentFilename()
    {
//         var_dump($this->getDataTransfer()->getResponse()->getHttpResponseCode());die;
        $templateName = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::TEMPLATE);
        return ($templateName !== null
            ? $templateName
            : $this->buildTemplateNameFromDataTransfer())
            . TemplateConst::EXTENSION_TWIG;
    }

    private function hasErrorsInResponse()
    {
        return $this->getDataTransfer()->getResponse()->getHttpResponseCode() < Http::CODE_200
            || $this->getDataTransfer()->getResponse()->getHttpResponseCode() >= Http::CODE_300;
    }

    private function buildTemplateNameFromDataTransfer()
    {
        //TODO: Drasko: this is temp solution!
        $parts = $this->hasErrorsInResponse()
            ? ['error', 'index']
            : [$this->getDataTransfer()->getRequest()->getResourceName(), $this->getDataTransfer()->getRequest()->getMethod()];
        return strtolower(join('/', $parts));
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
}