<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }
    //public function testIdeaCreate(){
    //        $response = $this->call('GET', '/idea/create');
    //        $this->assertEquals(200, $response->status());

    //}
    public function testIdeaStore(){
	    $response = $this->call('POST', '/idea/store', ['name' => 'Taylor']);
var_dump($response->status(), $response->getContent());

    }
}
