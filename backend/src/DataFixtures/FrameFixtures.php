<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Frame;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class FrameFixtures extends Fixture implements DependentFixtureInterface
{
    private $frames = [
        'https://cdn.discordapp.com/attachments/691374456513233036/959478774448140418/unknown.png',
        'https://cdn.discordapp.com/attachments/691374456513233036/959478775115055194/unknown.png',
        'https://cdn.discordapp.com/attachments/691374456513233036/959478775601590342/unknown.png',
        'https://cdn.discordapp.com/attachments/691374456513233036/959478776050364496/unknown.png',
        'https://cdn.discordapp.com/attachments/691374456513233036/959478776645951548/unknown.png',
        'https://cdn.discordapp.com/attachments/691374456513233036/959478777077968946/unknown.png',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->frames as $frameUrl) {
            $frame = new Frame();
            $frame->setImage($frameUrl);
            $frame->setMovie($this->getReference('movie_0'));
            $manager->persist($frame);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
        ];
    }
}
