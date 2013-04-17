<?php

namespace Epilgrim\ModifyRequestHeadersBundle\Tests\Listener;

use Epilgrim\ModifyRequestHeadersBundle\Listener\RequestListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class MenuPassTest extends \PHPUnit_Framework_TestCase
{
    public function testThatChecksRequestType()
    {
        $request = new Request();

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->atLeastOnce())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $listener = new RequestListener(array());
        $listener->onKernelRequest($event);
    }

    public function testDoesntDoAnythingIfNotMasterRequest(){
        $request = new Request();

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::SUB_REQUEST));
        $event->expects($this->never())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $listener = new RequestListener(array());

        $listener->onKernelRequest($event);
    }

    public function testAddsHeaderToTheRequest(){
        $headers = array('head1'=> array( 'name'=> 'head_random', 'value'=> 'value_random'));

        $request = new Request();

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $listener = new RequestListener($headers);

        $listener->onKernelRequest($event);

        $this->assertTrue($request->headers->has($headers['head1']['name']), 'The key is found in the Request');

        $this->assertEquals(
            $headers['head1']['value']
            , $request->headers->get($headers['head1']['name'])
            , 'The value is found in the corresponding key in the Request');
    }
}