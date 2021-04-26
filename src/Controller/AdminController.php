<?php

namespace App\Controller;


use App\Entity\Evenement;
use App\Entity\Formation;
use App\Entity\Oeuvrage;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\Registration;

use App\Form\UserType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use App\Repository\EvenementRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry ;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/Admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('Admin/adminutilisateurs.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

<?php

namespace App\Controller;

    use App\Entity\Utilisateur;
    use App\Form\LoginType;
    use App\Form\UtildashType;
    use App\Form\UtilisateurType;

    use App\Repository\EvenementRepository;
    use App\Repository\UtilisateurRepository;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request ;
    use Doctrine\Persistence\ManagerRegistry ;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="global")
     */
    public function index(EvenementRepository$repository): Response
    {

        return $this->render('Front/home.html.twig', [
            'evenements_approuver' => $repository->findOneBySomeField(1,1),
        ]);

    }

    /**
     * @Route("/admina", name="back")
     */
    public function aff(): Response
    {
        return $this->render('dashboard.html.twig');
    }




}


/**
 * @Route("/{userId}", name="admin_show", methods={"GET"})
 */
public function show(User $user): Response
{
    return $this->render('Admin/adminShow_user.html.twig', [
        'user' => $user,
    ]);
}
/**
 * @Route("/{userId}", name="admin_delete", methods={"GET"})
 */

public function delete(Request $request, User $user): Response
{
    if ($this->isCsrfTokenValid('delete'.$user->getUserId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setValidite(0);
        $entityManager->flush();
    }

    return $this->redirectToRoute('admin_index');
}


/**
 * @Route("/Admin/stats" , name="admin_stats")
 */
public function stat(UserRepository $userRepository){

    $users = $userRepository->findAll();
    $categNom = ["Formateur" , "Vendeur" , "Client"];
    $categColor = ["#343e3d",  "#414e4d" , "#a65959" ];

    $categFormateur = count($userRepository->findBy(["isFormateur" =>1]) )  ;
    $categVendeur = count($userRepository->findBy(["isVendeur" =>1]) ) ;
    $categClient = count($userRepository->findBy(["role" => "client"]) ) ;
    $categCount = [$categFormateur , $categVendeur , $categClient ];

    return $this->render('Admin/stat.html.twig', [
        'categNom' => json_encode($categNom),
        'categColor' => json_encode($categColor),
        'categCount' => json_encode($categCount),

    ]);
}

/**
 * @Route("/Admin/reclamation", name="admin_reclamation", methods={"GET"})
 */
public function reclamation(ReclamationRepository $reclamationRepository): Response
{

    return $this->render('Admin/adminreclamations.html.twig', [
        'reclamations' => $reclamationRepository->findAll(),
    ]);
}
/**
 * @Route("/Admin/reclamation/{reclamationId}", name="admin_reclamation_show", methods={"GET"})
 */
public function reclamation_show(Reclamation $reclamation): Response
{
    $repository0 = $this->getDoctrine()->getRepository(Formation::class);
    $repository1 = $this->getDoctrine()->getRepository(Evenement::class);

    $repository = $this->getDoctrine()->getRepository(Oeuvrage::class);
    if($reclamation->getOeuvrage()!=null){
        $Oeuvrage = $repository-> find($reclamation->getOeuvrage());
        $reclamation->setX("Oeuvre");
        $reclamation ->setconcernant($Oeuvrage);
    }
    if($reclamation->getFormation()!=null){
        $Formation = $repository0-> find($reclamation->getFormation());
        $reclamation->setX("Formation");
        $reclamation ->setconcernant($Formation);
    }
    if($reclamation->getEvenement()!=null){
        $Evenement = $repository1-> find($reclamation->getEvenement());
        $reclamation->setX("Evenement");
        $reclamation ->setconcernant($Evenement);
    }

    return $this->render('Admin/adminreclamation.html.twig', [
        'reclamation' => $reclamation,
    ]);
}
/**
 * @Route("/Admin/reclamation/{reclamationId}", name="admin_reclamation_avert", methods={"GET" ,"POST"})
 */
public function reclamation_avert(Request $request, Reclamation $reclamation) : Response
{

    if ($this->isCsrfTokenValid('Avertissement'.$reclamation->getReclamationId(), $request->request->get('_token'))){
        $reclamation->setAvertissement(1);
        $user1 = $reclamation->getUser();
        $user1->setAvertissement($user1->getAvertissement()+1);

        $this->getDoctrine()->getManager()->flush();}


    return $this->redirectToRoute('admin_reclamation_show', [
        'reclamationId' => $reclamation->getReclamationId(),
    ]);




}





}
