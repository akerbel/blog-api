<?php

namespace akerbel\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    const SUCCESS = ['result' => 'success'];
    const TEST_MESSAGE = [
        'title' => 'testMessageTitle',
        'text' => 'testMessageText',
    ];

    /**
     * Test adding of a message
     *
     * @return mixed
     */
    public function testPost()
    {
        $client = static::createClient();
        
        $client->request('POST', '/message', self::TEST_MESSAGE);
        $response = json_decode($client->getResponse()->getContent(), 1);
        $this->assertEquals('success', $response['result']);
        $this->assertNotNull($response['message_id']);

        return $response['message_id'];
    }

    /**
     * Test showing of a list of messages
     */
    public function testList()
    {
        $client = static::createClient();

        $client->request('GET', '/list', ['offset' => 0, 'limit' => 1]);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals('success', $response['result']);
        $this->assertNotNull($response['messages']);
    }

    /**
     * Test showing of one message
     *
     * @depends testPost
     */
    public function testGet($id)
    {
        $client = static::createClient();

        // Test wrong request
        $client->request('GET', '/message/0');
        $response = json_decode($client->getResponse()->getContent(), 1);
        $this->assertEquals('error', $response['result']);
        $this->assertEquals(404, $response['error_code']);

        // Test correct request
        $client->request('GET', '/message/'.$id);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals(self::TEST_MESSAGE['title'], $response['message']['title']);
        $this->assertEquals(self::TEST_MESSAGE['text'], $response['message']['text']);
        $this->assertNotEmpty($response['message']['created_at']);

        return $response['message'];
    }

    /**
     * Test patching of a message
     *
     * @depends testGet
     */
    public function testPatch($message)
    {
        // sleep for checking of changing of updated_at
        sleep(1);
        $client = static::createClient();

        // Test wrong request
        $client->request('PATCH', '/message/0', ['title' => self::TEST_MESSAGE['title'].'Patched', 'text' => self::TEST_MESSAGE['text'].'Patched']);
        $response = json_decode($client->getResponse()->getContent(), 1);
        $this->assertEquals('error', $response['result']);
        $this->assertEquals(404, $response['error_code']);

        // Test correct request
        $client->request('PATCH', '/message/'.$message['id'], ['title' => self::TEST_MESSAGE['title'].'Patched', 'text' => self::TEST_MESSAGE['text'].'Patched']);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals(self::SUCCESS, $response);

        // Check changing in the DB
        $client->request('GET', '/message/'.$message['id']);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals(self::TEST_MESSAGE['title'].'Patched', $response['message']['title']);
        $this->assertEquals(self::TEST_MESSAGE['text'].'Patched', $response['message']['text']);
        $this->assertEquals($message['created_at'], $response['message']['created_at']);
        $this->assertNotEquals($message['updated_at'], $response['message']['updated_at']);
    }

    /**
     * Test deleting of a message
     *
     * @depends testGet
     */
    public function testDelete($message)
    {
        $client = static::createClient();

        // Test wrong request
        $client->request('DELETE', '/message/0');
        $response = json_decode($client->getResponse()->getContent(), 1);
        $this->assertEquals('error', $response['result']);
        $this->assertEquals(404, $response['error_code']);

        // Test correct request
        $client->request('DELETE', '/message/'.$message['id']);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals(self::SUCCESS, $response);

        // Check changing in the DB
        $client->request('GET', '/message/'.$message['id']);
        $response = json_decode($client->getResponse()->getContent(), 1);

        $this->assertEquals('error', $response['result']);
        $this->assertEquals(404, $response['error_code']);

    }
}
