<?php

namespace G4\Runner\View\Response\Formatter;

class Verbose extends Basic
{
    /**
     * @return array
     */
    public function render()
    {
        // need to force proper order of keys
        $data = [
            'code'          => '',
            'message'       => '',
            'response'      => '',
            'app_code'      => '',
            'app_message'   => '',
            'params'        => '',
            'method'        => '',
            'resource_name' => '',
            'body_id'       => '',
            'profiler'      => '',
        ];

        $data = array_merge($data, parent::render());

        $data['app_code']      = $this->getApplication()->getResponse()->getApplicationResponseCode();
        $data['app_message']   = $this->getApplication()->getResponse()->getResponseMessage();
        $data['params']        = $this->getApplication()->getRequest()->getParamsAnonymized();
        $data['method']        = $this->getApplication()->getRequest()->getMethod();
        $data['resource_name'] = $this->getApplication()->getRequest()->getResourceName();

        $data['body_id'] = join("_", array(
            $this->getAppRunner()->getApplicationModule(),
            $this->getAppRunner()->getApplicationService(),
            $this->getAppRunner()->getApplicationMethod()
        ));

        return $data;
    }
}
