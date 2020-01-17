<?php

namespace G4\Runner\Presenter\View;

use G4\Constants\Override;
use G4\Constants\Template as TemplateConst;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewAbstract;
use G4\Runner\Presenter\View\ViewInterface;

class Image extends ViewAbstract implements ViewInterface
{
    /**
     * @return string
     */
    public function renderBody()
    {
        return $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::CONTENT);
    }
}