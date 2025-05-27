<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Form\StreamType;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stream')]
class StreamController extends AbstractController
{
    #[Route('/', name: 'app_stream_index', methods: ['GET'])]
    public function index(StreamRepository $streamRepository, Security $security): Response
    {
        // Check if the user is logged in
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('stream/index.html.twig', [
            'streams' => $streamRepository->findBy(['user' => $user->getUser()]),
        ]);
    }

    #[Route('/demain', name: 'app_stream_demain', methods: ['GET'])]
    public function demain(StreamRepository $streamRepository): Response
    {
        $demain = new \DateTime('tomorrow');
        $streams = $streamRepository->findAll();
        $streams = array_filter($streams, function (Stream $stream) use ($demain) {
            return $stream->getStartDate() ->format ('Y-M-D') === $demain->format('Y-m-d');
        });

        // $steamTomorrow = [];
        // foreach ($streams as $stream) {
        //     if ($stream->getStartDate() ->format('Y-m-d') === $demain->format('Y-m-d')) {
        //         $streamTomorrow[] = $stream;
        //     }
        // }
                

        //     $stream->setStartDate($stream->getStartDate()->setTime(0, 0, 0));
        // }

        // // Find streams that start tomorrow
        // $streamsDemain = $entityManager->getRepository(Stream::class)
        //     ->createQueryBuilder('s')
        //     ->join('App\Entity\Jeu', 'j')
        //     ->where('j.id = s.jeu')
        //     ->where('s.startDate >= :startDate')
        //     ->andWhere('s.startDate < :endDate')
        //     ->setParameter('startDate', $demain->setTime(0, 0, 0))
        //     ->setParameter('endDate', $demain->setTime(23, 59, 59))
        //     ->getQuery()
        //     ->getResult();

        return $this->render('stream/demain.html.twig', [
            'streams' => $streamsDemain,
        ]);
    }

    #[Route('/new', name: 'app_stream_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stream = new Stream();
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the current user as the stream owner
            $user = $this->getUser();
            $entityManager->persist($stream);
            $entityManager->flush();

            return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stream/new.html.twig', [
            'stream' => $stream,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stream_show', methods: ['GET'])]
    public function show(Stream $stream): Response
    {
        return $this->render('stream/show.html.twig', [
            'stream' => $stream,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stream_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stream $stream, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stream/edit.html.twig', [
            'stream' => $stream,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stream_delete', methods: ['POST'])]
    public function delete(Request $request, Stream $stream, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stream->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($stream);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stream_index', [], Response::HTTP_SEE_OTHER);
    }

}
