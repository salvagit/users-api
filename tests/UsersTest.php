<?php

require('vendor/autoload.php');

class UsersTest extends PHPUnit_Framework_TestCase
{

    var $id;
    var $url;

    protected $client;

    protected function setUp()
    {
        if (!empty(file_get_contents('lastInsertId'))) {
          $this->id = file_get_contents('lastInsertId');
        }
        if (!empty(getenv('url'))) $this->url = getenv('url');
        else return false;

        $this->client = new GuzzleHttp\Client([
            'base_uri' => $this->url
        ]);
    }

    /**
     * Test Post Method.
     */
    public function testPost_NewUser_UserObject()
    {
      $response = $this->client->request('POST', '/users', [
        'form_params' => [
          'name'    => 'Tito',
          'email' => 'tito@gmail.com',
          'image' => 'tito.jpg'
        ]
      ]);
      $this->assertEquals(200, $response->getStatusCode());
      $data = json_decode($response->getBody(), true);

      if(isset($data['data']['id'])) {
        $fo = fopen("lastInsertId", "w") or die("Unable to open file!");
        fwrite($fo, $data['data']['id']);
        fclose($fo);
      }
    }

    /**
     * Test PUT Method.
     */
    public function testPut_NewUser_UserObject()
    {
      if (empty($this->id)) return false;
      $response = $this->client->request('PUT', '/users/'.$this->id, [
        'form_params' => [
            'name'    => 'Tito2',
            'email' => 'tito2@gmail.com',
            'image' => 'tito2.jpg'
        ]
      ]);
      $this->assertEquals(200, $response->getStatusCode());
      $data = json_decode($response->getBody(), true);
    }

    /**
     * Test Get Method.
     */
    public function testGet_ValidInput_UserObject()
    {
        $url = $this->url;
        if (!empty($this->id)) $url .= '/'.$this->id;
        $response = $this->client->get($url);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('image', $data);
        $this->assertArrayHasKey('email', $data);

        $this->assertEquals('Tito2', $data['name']);
        $this->assertEquals('tito2@gmail.com', $data['email']);
    }


    /**
     * Test Delete Method.
     */
    public function testDelete_Error()
    {
      if (empty($this->id)) return false;

      $response = $this->client->delete('/users/'.$this->id, [
          'http_errors' => false
      ]);

      $this->assertEquals(200, $response->getStatusCode());
    }
}
