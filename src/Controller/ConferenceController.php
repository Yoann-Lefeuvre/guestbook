<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\SpamChecker;

class ConferenceController extends AbstractController
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager){
            $this->twig = $twig;
            $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return new Response( $this->twig->render('conference/index.html.twig',[
            'conferences'=> $conferenceRepository->findAll()
        ]) );
    }

    #[Route('/conference/{slug}', name:'conference')]
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository, SpamChecker $spamChecker, string $photoDir) : Response
    {     
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $comment->setConference($conference);
                    if ($photo = $form['photo']->getData()) {
                                        $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                                        try {
                                            $photo->move($photoDir, $filename);
                                        } catch (FileException $e) {
                                            // unable to upload the photo, give up
                                        }
                                        $comment->setPhotoFilename($filename);
                                    }
        
                    $this->entityManager->persist($comment);
                    
                    
                    //Identification du SPAM dans les commentaires
                    $context = [
                                        'user_ip' => $request->getClientIp(),
                                        'user_agent' => $request->headers->get('user-agent'),
                                        'referrer' => $request->headers->get('referer'),
                                        'permalink' => $request->getUri(),
                                    ];
                                    if (2 === $spamChecker->getSpamScore($comment, $context)) {
                                        throw new \RuntimeException('Blatant spam, go away!');
                                    }
                    

                    $this->entityManager->flush();
        
                    return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
                }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new response($this->twig->render('conference/show.html.twig',[
            'conference' => $conference, 
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form'=> $form->createView(),
        ]) );
    }
}
