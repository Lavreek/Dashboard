<?php

namespace App\Controller;

use App\Entity\Mails;
use App\Form\ControlType;
use App\Handler\ChartHandler;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class DashboardController extends AbstractController
{
    #[Route(['/', '/dashboard'], name: 'app_dashboard')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $control_form = $this->createForm(ControlType::class);
        $control_form->handleRequest($request);

        $chart = new ChartHandler();

        if ($control_form->isSubmitted()) {
            $task = $control_form->getData();

            $site = $task['site'];

            if (is_null($task['program'])) {
                /** @var \DateTime $dateStart */
                $dateStart = $task['date-start'];
                $dateEnd = $task['date-end'];

                $format = 'Y-m-d H:i:s';
                $mails = $managerRegistry->getRepository(Mails::class)
                    ->findBetween($dateStart->format($format), $dateEnd->format($format));

                dd($mails);
            } else {
                $selectedProgram = $task['program'];
            }

            var_dump(strtotime());
            dd($control_form->getData(), 'here');



            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                $chart->setChartData(
                    ['January', 'February', 'March', 'April', 'May', 'June'],
                    [
                        [
                            'label' => 'My First dataset',
                            'backgroundColor' => 'rgb(255, 99, 132)',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => [5, 2, 20, 30, 45, 33],
                        ]
                    ]
                );

                return $this->render('dashboard/dashboard.stream.html.twig', ['chart' => $chart->getChart()]);
            }
        }

        $chart->setChartData(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ]
            ]
        );

        return $this->render('dashboard/index.html.twig', [
            'chart' => $chart->getChart(),
            'control_form' => $control_form,
        ]);
    }
}
