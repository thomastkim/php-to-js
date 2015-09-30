<?php

namespace Kim\JavaScript;

use Illuminate\Events\Dispatcher;
use Illuminate\Session\Store as Session;

use InvalidArgumentException;

class VariableTransporter
{

    private $event;
    private $session;

    private $converter;

    private $view;

    public function __construct(Dispatcher $event, JavaScriptSession $session, VariableConverter $converter, $view = '')
    {
        $this->event = $event;
        $this->session = $session;
        $this->converter = $converter;
        $this->view = $view;

        $this->injectScript();
    }

    public function put($data = [])
    {
        foreach ($data as $key => $value)
        {
            $this->add($key, $value);
        }
    }

    public function add($key, $value)
    {
        $data = $this->session->getData();

        array_push($data, $this->converter->convertToJavascript($key, $value));

        $data = $this->removeDuplicates($data);

        $this->session->flash($data);
    }

    private function removeDuplicates($data)
    {
        return array_unique($data);
    }

    private function injectScript()
    {
        $session = $this->session;

        if ($this->view === '')
        {
            $this->event->listen('router.after', function($route, $request) use ($session)
            {

                if (method_exists($request,'getOriginalContent'))
                {
                    $javascript = $session->getCompiledData();
                    $output = $request->getOriginalContent();
                    $output = str_replace("</head>","<script>{$javascript}</script></head>", $output);
                    $request->setContent($output);
                    $session->flush();
                }
            });
        }
        else if (view()->exists($this->view)) {

            $this->event->listen("creating: {$this->view}", function() use ($session)
            {

                $javascript = $session->getCompiledData();

                echo "<script>{$javascript}</script>";

                $session->flush();
            });

        }
    }

}