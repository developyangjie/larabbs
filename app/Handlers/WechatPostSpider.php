<?php
namespace App\Handlers;

use Goutte\Client;

class WechatPostSpider{
    protected $crawler;

    protected $url;

    public function  __construct(Client $client,$url)
    {
        $this->url = $url;
        $this->crawler = $client->request('GET',$url);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return trim($this->crawler->filter('title')->text());
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return trim($this->crawler->filter('.rich_media_content')->text());
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return trim($this->crawler->filter('#js_reward_author')->text());
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}