<?php
namespace Epilgrim\ModifyRequestHeadersBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class RequestListener {

    protected $headers = array();
    protected $event;

    public function __construct(array $headers ) {
        $this->newHeaders = $headers;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->event = $event;

        if (HttpKernel::MASTER_REQUEST == $this->event->getRequestType()) {
            $this->doUpdateHeaders();
        }
     }

     /**
      * goes through all the new headers, and stores them in the request
      * @return [type] [description]
      */
     private function doUpdateHeaders(){
        $headers = $this->event->getRequest()->headers;
        foreach ($this->newHeaders as $header){
            $headers->set($header['name'], $header['value']);
        }
     }

}