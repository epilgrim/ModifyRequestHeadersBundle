<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Epilgrim\ModifyRequestHeadersBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Epilgrim\ModifyRequestHeadersBundle\DependencyInjection\EpilgrimModifyRequestHeadersExtension;
use Symfony\Component\Yaml\Parser;

class EpilgrimModifyRequestHeadersExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $configuration;

    public function testListenerNotLoadedIfNoHeaders()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new EpilgrimModifyRequestHeadersExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertNotHasDefinition('epilgrim.modify_request_headers.class');
    }

    public function testListenerLoadedIfThereIsHeaders()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new EpilgrimModifyRequestHeadersExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertHasDefinition('epilgrim.modify_request_headers.class');
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsIfNoValueGivenToHeader()
    {
        $loader = new EpilgrimModifyRequestHeadersExtension();
        $config = $this->getFullConfig();
        unset($config['headers'][0]['value']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsIfNoNameGivenToHeader()
    {
        $loader = new EpilgrimModifyRequestHeadersExtension();
        $config = $this->getFullConfig();
        unset($config['headers'][0]['name']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF

EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
headers:
    - {name: x_forwarded_proto, value: https}
    - {name: x_forwarded_port, value: 8443}
    - {name: x_forwarded_host, value: epilgrim.com}
listener_priority: 255
EOF;
        $parser = new Parser();

        return  $parser->parse($yaml);
    }

    /**
     * @param string $value
     * @param string $key
     */
    private function assertAlias($value, $key)
    {
        $this->assertEquals($value, (string) $this->configuration->getAlias($key), sprintf('%s alias is correct', $key));
    }

    /**
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    /**
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertFalse(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
