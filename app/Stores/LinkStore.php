<?php

namespace App\Stores;

use Doctrine\DBAL\Connection;
use Carc1n0gen\ShortLink\Converter;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Common\AbstractCachePool;

class LinkStore
{
    protected $shortlink;
    protected $db;
    protected $cache;

    public function __construct(Converter $shortlink, Connection $db, AbstractCachePool $cache)
    {
        $this->shortlink = $shortlink;
        $this->db = $db;
        $this->cache = $cache;
    }

    /**
     * Find a full url from it's shortLink
     *
     * @param string $shortLink The encoded shortlink
     * @return string The url if found
     * @return null if not found 
     */
    public function find($shortLink)
    {
        if ($url = $this->cache->getItem($shortLink)->get()) {
            return $url;
        }

        $id = $this->shortlink->decode($shortLink);
        $link = $this->db->createQueryBuilder()
            ->select('*')
            ->from('links')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->execute()
            ->fetch();

        if ($link) {
            $this->cache->save(new CacheItem($shortLink, true, $link['url']));
            return $link['url'];
        }

        return null;
    }

    /**
     * Look for a url by looking up the md5 of the url and create a new record
     * if nothing is found.
     *
     * @param string $url The URL to find or create
     * @return string The encoded "shortlink" for the url
     */
    public function findOrCreate($url)
    {
        // If the url does not include the protocol, assume http
        if (!preg_match('#^(.*:)?//#i', $url)) {
            $url = "http://$url";
        }

        $md5 = md5($url);
        if ($shortLink = $this->cache->getItem($md5)->get()) {
            return $shortLink;
        }

        $link = $this->db->createQueryBuilder()
            ->select('*')
            ->from('links')
            ->where('md5 = ?')
            ->setParameter(0, $md5)
            ->execute()
            ->fetch();

        if ($link) {
            $shortLink = $this->shortlink->encode($link['id']);
        } else {
            $shortLink = $this->create($url, $md5);
        }

        $this->cache->save(new CacheItem($md5, true, $shortLink));
        return $shortLink;
    }

    /**
     * Create a shortlink record from a url and return the encoded shortlink
     *
     * @param string $url The url to generate a shortlink for
     * @param string $md5 (optional) The md5 of the url
     * @return string The encoded shortlink of the url
     */
    public function create($url, $md5 = null) {
        if (!$md5) {
            $md5 = md5($url);
        }

        $this->db->createQueryBuilder()
            ->insert('links')
            ->values([
                'md5' => '?',
                'url' => '?',
            ])
            ->setParameter(0, $md5)
            ->setParameter(1, $url)
            ->execute();

        return $this->shortlink->encode($this->db->lastInsertId());
    }
}
