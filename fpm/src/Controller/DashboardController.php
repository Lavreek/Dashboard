<?php

namespace App\Controller;

use App\Entity\Calls;
use App\Entity\Mails;
use App\Entity\Site;
use App\Form\ControlType;
use App\Handler\ChartHandler;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    const day = 86400;
    const full_format = 'Y-m-d H:i:s';
    const short_format = 'Y-m-d';
    
    #[Route(['/', '/dashboard'], name: 'app_dashboard')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $dateStart_date = date(self::short_format, strtotime('-7 days'));
        $dateEnd_date = date(self::short_format, strtotime('+1 days'));

        $controlForm = $this->createForm(ControlType::class);
        $controlForm->handleRequest($request);

        $chart = new ChartHandler();

        $site = $managerRegistry->getRepository(Site::class)->findOneBy([], ['id' => 'ASC']);

        if ($controlForm->isSubmitted()) {
            $task = $controlForm->getData();

            $site = $task['site'];

            if (is_null($task['program'])) {
                $dateStart_date = $task['dateStart']->format(self::short_format);
                $dateEnd_date = $task['dateEnd']->format(self::short_format);

            } else {
                $selectedProgram = $task['program'];

                switch ($selectedProgram) {
                    case '0' : {
                        $dateStart_date = date(self::short_format);
                        $dateEnd_date = date(self::short_format, strtotime('+1 days'));
                        break;
                    }

                    case '-1' : {
                        $dateStart_date = date(self::short_format, strtotime('-1 days'));
                        $dateEnd_date = date(self::short_format);
                        break;
                    }

                    case '-7' : {
                        $dateStart_date = date(self::short_format, strtotime('-7 days'));
                        $dateEnd_date = date(self::short_format);
                        break;
                    }

                    case '-30' : {
                        $dateStart_date = date(self::short_format, strtotime('-30 days'));
                        $dateEnd_date = date(self::short_format);
                        break;
                    }

                    case '30' : {
                        $dateStart_time = time();
                        $month = date('m');

                        $i = 0;
                        while (date('m', $dateStart_time) == $month) {
                            $dateStart_time -= self::day;

                            $i++;
                            if ($i > 100) {
                                break;
                            }
                        }

                        $dateStart_date = date(self::short_format, $dateStart_time);
                        $dateEnd_date = date(self::short_format);
                        break;
                    }
                }
            }
        }

        $days = (strtotime($dateEnd_date) - strtotime($dateStart_date)) / self::day;

        $mails = $this->getBetween(
            $managerRegistry, Mails::class, $dateStart_date, $dateEnd_date, $site->getId(), $days
        );

        $calls = $this->getBetween(
            $managerRegistry, Calls::class, $dateStart_date, $dateEnd_date, $site->getId(), $days
        );

        $mailsData = [
            'label' => "Письма",
            'backgroundColor' => 'rgb(255, 99, 132)',
            'borderColor' => 'rgb(255, 99, 132)',
            'data' => [],
        ];

        $callsData = [
            'label' => "Звонки",
            'backgroundColor' => 'rgb(255, 132, 99)',
            'borderColor' => 'rgb(255, 132, 99)',
            'data' => [],
        ];

        $heading = $mailsByDate = $callsByDate = [];

        $dateStart_time = strtotime($dateStart_date);

        $totalMails = $totalCalls = 0;

        for ($i = 0; $i < $days; $i++) {
            $heading[] = date(self::short_format, $dateStart_time);

            $mailsData['data'][$i] = 0;
            $callsData['data'][$i] = 0;

            foreach ($mails as $mail) {
                if ($mail->getDate()->format(self::short_format) == $heading[count($heading) - 1]) {
                    $mailsData['data'][$i] = $mail->getCount();
                }
            }

            foreach ($calls as $call) {
                if ($call->getDate()->format(self::short_format) == $heading[count($heading) - 1]) {
                    $callsData['data'][$i] = $call->getCount();
                }
            }

            $totalMails += $mailsData['data'][$i];
            $totalCalls += $callsData['data'][$i];

            $mailsByDate[$heading[count($heading) - 1]] = $mailsData['data'][$i];
            $callsByDate[$heading[count($heading) - 1]] = $callsData['data'][$i];

            $dateStart_time += self::day;
        }

        $chart->setChartData( $heading, [ $mailsData,  $callsData, ] );

        return $this->render('dashboard/index.html.twig', [
            'chart' => $chart->getChart(),
            'date_start' => $dateStart_date,
            'date_end' => $dateEnd_date,
            'control_form' => $controlForm,
            'heading' => $heading,
            'site' => $site,
            'mails' => $mailsByDate,
            'calls' => $callsByDate,
            'total_mails' => $totalMails,
            'total_calls' => $totalCalls,
        ]);
    }

    private function getBetween($manager, $class, $start, $end, $id, $limit )
    {
        $start_formated = date('Y-m-d H:i:s', strtotime($start));
        $end_formated = date('Y-m-d H:i:s', strtotime($end));

        return $manager->getRepository($class)->findBetween( $start_formated, $end_formated, $id, $limit );
    }
}
