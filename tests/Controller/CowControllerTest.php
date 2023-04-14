<?php

namespace App\Test\Controller;

use App\Entity\Cow;
use App\Repository\CowRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CowControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CowRepository $repository;
    private string $path = '/cow/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Cow::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cow index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'cow[weight]' => 'Testing',
            'cow[milkAmount]' => 'Testing',
            'cow[foodAmount]' => 'Testing',
            'cow[born]' => 'Testing',
            'cow[isAlive]' => 'Testing',
        ]);

        self::assertResponseRedirects('/cow/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cow();
        $fixture->setWeight('My Title');
        $fixture->setMilkAmount('My Title');
        $fixture->setFoodAmount('My Title');
        $fixture->setBorn('My Title');
        $fixture->setIsAlive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cow');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cow();
        $fixture->setWeight('My Title');
        $fixture->setMilkAmount('My Title');
        $fixture->setFoodAmount('My Title');
        $fixture->setBorn('My Title');
        $fixture->setIsAlive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cow[weight]' => 'Something New',
            'cow[milkAmount]' => 'Something New',
            'cow[foodAmount]' => 'Something New',
            'cow[born]' => 'Something New',
            'cow[isAlive]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cow/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getWeight());
        self::assertSame('Something New', $fixture[0]->getMilkAmount());
        self::assertSame('Something New', $fixture[0]->getFoodAmount());
        self::assertSame('Something New', $fixture[0]->getBorn());
        self::assertSame('Something New', $fixture[0]->getIsAlive());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Cow();
        $fixture->setWeight('My Title');
        $fixture->setMilkAmount('My Title');
        $fixture->setFoodAmount('My Title');
        $fixture->setBorn('My Title');
        $fixture->setIsAlive('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/cow/');
    }
}
