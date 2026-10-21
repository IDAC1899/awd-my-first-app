<?php

namespace App\Controller;

use App\Entity\Announcement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function about(): Response
    {
        // list of team members to show on the about page
        $team = array(
            "Andrei",
            "Kingsley",
            "Julie",
            "Kacper",
            "Rebecca",
            "Junaid",
            "Jesse",
            "Oliver",
            "Nathan"
        );

        // pass the team array to twig under the name team
        return $this->render('default/about.html.twig', [
            'team' => $team
        ]);
    }

    // exercise 1: a static route set with a php attribute instead of routes.yaml
    #[Route('/hello/world', name: 'hello_world')]
    public function helloAction(): Response
    {
        return $this->render('default/hello.html.twig');
    }

    // exercise 2: {name} in routes.yaml is passed in here as $name
    public function helloName(string $name): Response
    {
        return $this->render('default/hello_name.html.twig', [
            'name' => $name
        ]);
    }

    // exercise 3: routes.yaml only lets one digit (0-9) through, so $number is always 0 to 9
    public function increment(int $number): Response
    {
        $result = $number + 1;

        return $this->render('default/increment.html.twig', [
            'result' => $result
        ]);
    }

    // exercise 4: the salutation can only be hello, hi or welcome, the name can be anything
    public function greeting(string $salutation, string $name): Response
    {
        return $this->render('default/greeting.html.twig', [
            'salutation' => $salutation,
            'name' => $name
        ]);
    }

    // exercise 5: a two-dimensional array, each contact is its own array with a name and email
    public function contacts(): Response
    {
        $contacts = [
            [
                'name' => 'Jane Smith',
                'email' => 'jane@smith.com'
            ],
            [
                'name' => 'John Smith',
                'email' => 'john@smith.com'
            ],
            [
                'name' => 'Bill Gates',
                'email' => 'bill@microsoft.com'
            ]
        ];

        // exercise 6: sends the array to the profiler's debug panel instead of printing it on the page
        dump($contacts);

        return $this->render('default/contacts.html.twig', [
            'contacts' => $contacts
        ]);
    }

    // doctrine exercise 3: creates an announcement with the message from the url and the current time
    public function newAnnouncement(
        string $message,
        EntityManagerInterface $entityManager
    ): Response {
        $announcement = new Announcement();
        $announcement->setMessage($message);
        $announcement->setTimestamp(new \DateTime());

        // persist() schedules the insert, flush() runs it on the database
        $entityManager->persist($announcement);
        $entityManager->flush();

        return new Response('Announcement saved: ' . $message);
    }

    // doctrine exercise 4a: finds one announcement by its id
    public function showAnnouncement(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $announcement = $entityManager
            ->getRepository(Announcement::class)->find($id);

        // no row with that id, so show a 404 page
        if (!$announcement) {
            throw $this->createNotFoundException(
                'No announcement found for id ' . $id
            );
        }

        return $this->render('default/show_announcement.html.twig', [
            'announcement' => $announcement,
        ]);
    }

    // doctrine exercise 4b: gets every announcement in the table
    public function allAnnouncements(
        EntityManagerInterface $entityManager
    ): Response {
        $announcements = $entityManager
            ->getRepository(Announcement::class)->findAll();

        return $this->render('default/all_announcements.html.twig', [
            'announcements' => $announcements,
        ]);
    }
}
