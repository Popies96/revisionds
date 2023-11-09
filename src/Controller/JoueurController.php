<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\FormType;
use App\Repository\JoueurRepository;
use App\Repository\VoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    /**
     * @Route("/joueur", name="app_joueur")
     */
    public function index(): Response
    {
        return $this->render('joueur/index.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }
    /**
     * @Route("/list", name="list_joueur")
     */
    public function list(JoueurRepository $jr,VoteRepository $vt ,ManagerRegistry $mr,Request $request): Response
    {
        $vote = new Vote();
        $form = $this->createForm(FormType::class,$vote);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $vote->setDate(new\ DateTime("now") );
            $em = $mr->getManager();
            $joueurVote = $vote->getJoueur();
            $nbrVote = $vote->getNoteVote();

            $em->persist($vote);

            $joueurVote->setMoyenneVote($vt->getSommeVotebyJoueur($joueurVote->getId()));
            $em->flush();
        }

        return $this->render('joueur/list.html.twig', [
            'j' => $jr->FindBy ((array ()), array ('nom' => 'ASC')),
            'f' => $form->createView()
        ]);
    }
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail($id , VoteRepository $vt , JoueurRepository $jr ): Response
    {
        $vr = $vt->getSommeVotebyJoueur($id);
$em = $jr->find($id);


        return $this->render('vote/detail.html.twig', [
'j' => $em ,
            'v' => $vr
        ]);
    }
}
