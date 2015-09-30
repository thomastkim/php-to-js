<?php

namespace Kim\JavaScript;

use Illuminate\Session\Store;

class JavaScriptSession
{

    private $session;

    private $javascript;

    public function __construct(Store $session, $namespace = 'window')
    {
        $this->session = $session;

        $this->javascript = "var {$namespace} = {$namespace} || [];";
    }

    public function getData()
    {
        return $this->session->get('javascript.data', []);
    }

    public function flash($data)
    {
        $this->session->flash('javascript.data', $data);
    }

    public function getCompiledData()
    {
        return $this->javascript . join('', $this->session->get('javascript.data'));
    }

    public function flush()
    {
        $this->session->flush();
    }
}