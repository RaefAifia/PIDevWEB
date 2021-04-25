<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Fpdf\Fpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements_approuver' => $evenementRepository->findOneBySomeField(1,1),
        ]);
    }
    /**
     * @Route("/rechercheevent", name="rechercheevent")
     */
    public function searchuCategorieAction(Request $request,EvenementRepository $repository){
        $em = $this->getDoctrine()->getManager();


        $searchParameter = $request->get('event');
        if(strlen($searchParameter)==0)
            $entities = $em->getRepository(Evenement::class)->findAll();
        else

            //call repository function

            $entities = $repository->findByExampleField($searchParameter);



        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entities, 'json',['ignored_attributes'=>['lieuId','idArtiste']

        ]);

        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }

    /**
     * @Route("/back", name="evenement_index_back", methods={"GET"})
     */
    public function indexback(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/back.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Front", name="evenement_index_Front", methods={"GET"})
     */
    public function indexFront(EvenementRepository $evenementRepository,ReservationRepository $repository): Response
    {
        $events=$evenementRepository->findAll();

        $my_array = array();
        foreach ($events as $e)
        {  $reservee=$repository->findOneBySomeField($e->getEvenementId(),1);

        if($reservee)
        {
            array_push($my_array,$e);

        }


        }
        return $this->render('evenement/frontApprouver.html.twig', [
            'evenements' => $evenementRepository->findBy(array('etat'=>1)),
            'islike'=>$my_array
        ]);
    }


    /**
     * @Route("/new", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $repository,EvenementRepository$evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$evenement->getImageFile();
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $evenement->setImage($filename);
            $evenement->setDateCreation(new \DateTime());
            $evenement->setIdArtiste($repository->find(1));
            $evenement->setEtat(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
            'evenements_approuver' => $evenementRepository->findOneBySomeField(1,1),

        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement,EvenementRepository$evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file=$evenement->getImageFile();
            if($file)
            {
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $evenement->setImage($filename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
            'evenements_approuver' => $evenementRepository->findOneBySomeField(1,1),

        ]);
    }

    /**
     * @Route("/delete/{id}", name="evenement_delete")
     */
    public function delete(Request $request, Evenement $evenement,ReservationRepository$repository): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            foreach ($repository->findBy(array('evenement'=>$evenement)) as $value)
            {
                $entityManager->remove($value);
            }
            $entityManager->remove($evenement);
            $entityManager->flush();


        return $this->redirectToRoute('evenement_index');
    }
    /**
     * @Route("/approuver/{id}", name="evenement_approuver")
     */
    public function approuver(Request $request, Evenement $evenement): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $evenement->setEtat(1);
        $entityManager->flush();


        return $this->redirectToRoute('evenement_index_back');
    }
    /**
     * @Route("/rejeter/{id}", name="evenement_rejeter")
     */
    public function evenement_rejeter(Request $request, Evenement $evenement): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $evenement->setEtat(0);
        $entityManager->flush();


        return $this->redirectToRoute('evenement_index_back');
    }
    /**
     * @Route("/reserver/{id}", name="evenement_reserver")
     */
    public function evenement_reserver(Request $request, Evenement $evenement,UserRepository$userRepository): Response
    {
        $reservation=New Reservation();
        $reservation->setDate(new \DateTime);
        $reservation->setUser($userRepository->find(1));
        $reservation->setEvenement($evenement);
        $reservation->setNumReserv($evenement->getTitre().$reservation->getUser()->getUsername());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $evenement->setCapacite($evenement->getCapacite()-1);
        $entityManager->flush();




        return $this->redirectToRoute('evenement_index_Front');
    }
    /**
     * @Route("/reservationDelete/{id}", name="evenement_reserver_delete")
     */
    public function evenement_delete_reservation(Request $request,ReservationRepository $repository,Evenement $evenement): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $evenement->setCapacite($evenement->getCapacite()+1);

        $entityManager->remove($repository->findOneBySomeField($evenement->getEvenementId(),1) );
        $entityManager->flush();


        return $this->redirectToRoute('evenement_index_Front');
    }

    /**
     * @Route("/voirTicket/{id}", name="evenement_reserver_voirTicket")
     */
    public function evenement_ticket_reservation(Request $request,ReservationRepository $repository,Evenement $evenement): Response
    {

        $reservation=$repository->findOneBySomeField($evenement->getEvenementId(),1);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data('Event : '.$evenement->getTitre().PHP_EOL.'Description:'.$evenement->getDescription()
                .PHP_EOL.'User:'.$reservation->getUser()->getUsername())
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText('Scanner le ticket')
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();
        header('Content-Type: '.$result->getMimeType());

        $result->saveToFile($this->getParameter('images_directory').'/'.$reservation->getReservationId().$evenement->getTitre().'.png');


        $pdf = new FPDF('P','mm', 'a5');
        $pdf->SetTopMargin(75);
        $pdf->AddPage();
        $pdf->Image($this->getParameter('images_directory').'/'."QRCodeTemplate.jpg",0,0,150,210);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(0,10,"Reservation Pour,",0,0,'C');
        $pdf->Ln();


        $pdf->Image($this->getParameter('images_directory').'/'.$reservation->getReservationId().$evenement->getTitre().'.png',38,100,75,75);
        $pdf->Cell(0,10,"evenement:".$evenement->getTitre(),0,0,'C');
        $this->redirect('/evenement/Front');
        $pdf->Output("someqrpdf.pdf", "D");


        return $this->redirectToRoute('evenement_index_Front');
    }



}
