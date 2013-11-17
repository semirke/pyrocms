<?php

use Goutte\Client;

class CreateValidUserTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new Client();
        $this->client->followRedirects(true);
    }

    public function tearDown()
    {
        unset($this->client);
    }

    public function navigateToStepFour()
    {
        $crawler = $this->client->request('GET', 'http://' . PYRO_HOST);
        $form = $crawler->selectButton('Step 2')->form();
        $formFields = array(
            'username' => 'pyrocms',
            'password' => 'password',
            'database' => 'pyrocms',
            'create_db'=> 1 ,
            'hostname' => PYRO_DB_HOST,
            'db_driver' => 'mysql',
            'port' => '3306'
        );
        $crawler = $this->client->submit($form,$formFields);

        return $crawler;
    }
    /**
     * @test
     * Given that the database settings are successful
     * When the user form is filled out
     * Then the user should be created and user taken to confirmation page
     */
    public function CreateAdminUserInInstaller()
    {
        $crawler = $this->navigateToStepFour();
        $form = $crawler->selectButton('Install')->form();
        $formFields = array(
            'user[username]'=>'admin',
            'user[firstname]'=> 'Pyro',
            'user[lastname]' => 'Admin',
            'user[email]' => 'admin@admin.com',
            'user[password]' => 'administrator'
        );
        //submit user forms
        $crawler = $this->client->submit($form,$formFields);
    }
}
