<?php

use Goutte\Client;

class TestInstallerValidDbCreds extends PHPUnit_Framework_TestCase
{
    /**
     * @group installer
     * @group all
     */
    public function setUp()
    {
        $this->client = new Client();
    }

    public function tearDown()
    {
        unset($this->client);
    }

    /** 
     * @test
     * Given a fresh Pyro install
     * When a user provides valid db auth credentials
     * Then the install should authenticate that db user and continue
     */
    public function DatabaseAuthenticationWithValidCreds()
    {
        $formFields = array(
            'db_driver' => 'mysql',
            'hostname' => PYRO_DB_HOST,
            'username' => 'pyrocms',
            'password' => 'password',
            'create_db' => 1,
            'database' => 'pyrocms',
            'port' => '3306'

        );
        $crawler = $this->client->request('GET', 'http://'.PYRO_HOST);
        $this->assertEquals($crawler->filter('title')->text(),'PyroCMS Installer');
        $form = $crawler->selectButton('Step 2')->form();
        $crawler = $this->client->submit($form,$formFields);
        $this->assertContains('Step 4:',$crawler->filter('.title h3')->text());
    }
}
