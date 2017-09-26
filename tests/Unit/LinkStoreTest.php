<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Doctrine\DBAL\Connection;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Common\AbstractCachePool;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class LinkStoreTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = self::createApplication();
    }

    public function testFindShouldFindUrl()
    {
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $this->assertNotNull($linkStore->find('b'));
    }

    public function testFindShouldReturnNull()
    {
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $this->assertNull($linkStore->find('zzz'));
    }

    public function testFindShouldCheckDatabase()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn(null)->once();
        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldReceive('createQueryBuilder->select->from->where->setParameter->execute->fetch')->once();

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->find('z');
    }

    public function testFindShouldNotCheckDatabase()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn('www.example.com')->once();
        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldNotReceive('createQueryBuilder');

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->find('z');
    }

    public function testFindShoulStoreInCache()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn(null)->once();
        $cacheMock->shouldReceive('save')->once();
        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldReceive('createQueryBuilder->select->from->where->setParameter->execute->fetch')
            ->andReturn([
                'id' => 5,
                'url' => 'www.example.com',
                'md5' => 'thisIsNotARealMd5',
            ]);

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->find('z');
    }

    //

    // TODO: Come back to this and add assertion that create did not occur.
    // Right now the test is just here for coverage of that case whith no
    // actual assertion :(
    public function testFindOrCreateShouldNotCreate()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn(null)->once();
        $cacheMock->shouldReceive('save')->once();

        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldReceive('createQueryBuilder->select->from->where->setParameter->execute->fetch')
            ->andReturn([
                'id' => 45,
                'url' => 'www.omg-wtf-cool.com',
                'md5' => 'NotARealMd5',
            ]);

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->findOrCreate('ww.omg-wtf-cool.com');
    }

    public function testFindOrCreateShouldCheckDatabaseAndStoreInCache()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn(null)->once();
        $cacheMock->shouldReceive('save')->once();
        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldReceive('createQueryBuilder->select->from->where->setParameter->execute->fetch')
            ->once();
        $databaseMock->shouldReceive('createQueryBuilder->insert->values->setParameter->setParameter->execute')
            ->once();
        $databaseMock->shouldReceive('lastInsertId')->andReturn(55);

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->findOrCreate('www.example.com');
    }

    public function testFindOrCreateShouldNotCheckDatabase()
    {
        $cacheMock = Mockery::mock(AbstractCachePool::class);
        $cacheMock->shouldReceive('commit');
        $cacheMock->shouldReceive('getItem->get')->andReturn('c')->once();
        $databaseMock = Mockery::mock(Connection::class);
        $databaseMock->shouldNotReceive('createQueryBuilder');

        $this->app->getContainer()['cache'] = $cacheMock;
        $this->app->getContainer()['db'] = $databaseMock;
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->findOrCreate('www.example.com');
    }

    //

    public function testCreateNotThrowUniqueConstraintViolationException()
    {
        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->create('www.some-wacky-url.com');
    }

    public function testCreateShouldThrowUniqueConstraintViolationException()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $linkStore = $this->app->getContainer()->get('LinkStore');
        $linkStore->create('www.some-wacky-url2.com');
        $linkStore->create('www.some-wacky-url2.com');
    }
}