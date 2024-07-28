<?php

namespace App\Services;

use GuzzleHttp\Client;

class InstagramScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getVideoUrl($postUrl)
    {
        $response = $this->client->get($postUrl);
        $html = (string) $response->getBody();

        preg_match('/"src":"(.*?)"/', $html, $matches);
        if (isset($matches[1])) {
            return stripslashes($matches[1]);
        }

        throw new \Exception('No video found in this post');
    }
}
