<?php

namespace App\Stores;

use App\Component;

class LinkStore extends Component
{
    /**
     * Find a full url from it's shortLink
     *
     * @param string $shortLink The encoded shortlink
     * @return string The url if found
     * @return null if not found 
     */
    public function find($shortLink)
    {
        // TODO: Check in cache first

        $id = $this->shortlink->decode($shortLink);
        $link = $this->db->createQueryBuilder()
            ->select('*')
            ->from('links')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->execute()
            ->fetch();

        if ($link) {
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
        // TODO: I could probably utilize cache based on the md5

        // If the url does not include the protocol, assume http
        if (!preg_match('#^(.*:)?//#i', $url)) {
            $url = "http://$url";
        }

        $md5 = md5($url);

        $link = $this->db->createQueryBuilder()
            ->select('*')
            ->from('links')
            ->where('md5 = ?')
            ->setParameter(0, $md5)
            ->execute()
            ->fetch();

        if ($link) {
            return $this->shortlink->encode($link['id']);
        }

        return $this->create($url, $md5);
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
