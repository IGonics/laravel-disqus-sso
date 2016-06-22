<?php

use Modbase\Disqus\DisqusServiceProvider;

class DisqusServiceTestCase extends Orchestra\Testbench\TestCase {
	
	protected $service = null;

	protected $testUserData = [
	    'id'       => 1, 
	    'username' => 'testuser', 
	    'email'    => 'testuser@test.com', 
	    'avatar'   => 'http://test.com/test-avatar-url', 
	    'url'      => 'http://test.com/'
	];

	protected $testPrivateKey = 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest';
    protected $testPublicKey = 'tsettsettsettsettsettsettsettsettsettsettsettsettsettsettsettset';

    public function setUp()
    {
        parent::setUp();

        $this->service = App::make('disqus-sso');
    }

    public function testCreate()
    {
        $this->assertNotNull($this->service);
    }

    public function testPayload(){
        $timestamp = time();
        $encodedData = $this->service->getEncodedData($this->testUserData);
        $expected = $encodedData . ' ' . $this->service->getHMAC($encodedData, $timestamp) . ' ' . $timestamp;

        $this->assertTrue($expected == $this->service->payload($this->testUserData));
    }

    public function testPublicKey()
    {
    	$this->assertTrue($this->service->publicKey() === $this->testPublicKey);
    }

    public function testGetEncodedData()
    {
        $expected = base64_encode(json_encode($this->testUserData));
        $this->assertTrue($expected === $this->service->getEncodedData($this->testUserData));
    }

    public function testGetHMAC()
    {
    	$timestamp = time();
    	$encodedData = $this->service->getEncodedData($this->testUserData);
        $message = $encodedData . ' ' . $timestamp;
        $expected = hash_hmac('sha1', $message, $this->testPrivateKey);
        $this->assertTrue($expected == $this->service->getHMAC($encodedData, $timestamp));
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('disqus-sso::key.private', $this->testPrivateKey);
        $app['config']->set('disqus-sso::key.public', $this->testPublicKey);
    }

    protected function getPackageProviders($app)
    {
        return [
             DisqusServiceProvider::class,
        ];
    }
}