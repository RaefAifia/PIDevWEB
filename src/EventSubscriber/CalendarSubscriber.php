<?php

namespace App\EventSubscriber;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $offreRepository;
    private $router;

    public function __construct(
        OffreRepository $offreRepository,
        UrlGeneratorInterface $router
    ) {
        $this->offreRepository= $offreRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        /* $evenements = $this->evenementRepository
             ->createQueryBuilder('Evenement')
             ->where('Evenement.datedeb BETWEEN :start and :end OR Evenement.datefin BETWEEN :start and :end')
             ->setParameter('start', $start->format('Y-m-d H:i:s'))
             ->setParameter('end', $end->format('Y-m-d H:i:s'))
             ->getQuery()
             ->getResult()
         ;*/
        $offres = $this->offreRepository->findAll();

        foreach ($offres as $offre) {
            // this create the events with your data (here booking data) to fill calendar
            $evenementEvent = new Event(
                $offre->getNom(),
               // $offre->getDescription(),
                $offre->getDate(),
                $offre->getDate() // If the end date is null or not defined, a all day event is created.
            );


            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $evenementEvent->setOptions([
                'backgroundColor' => '#a65959',
                'borderColor' => '#556B2F',

            ]);
            //'textColor'=>'purple'
            $evenementEvent->addOption(
                'url',
                $this->router->generate('offre_show', [
                    'offreId' => $offre->getOffreId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($evenementEvent);
        }
    }
}