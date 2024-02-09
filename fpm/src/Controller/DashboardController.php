<?php

namespace App\Controller;

use App\Form\ControlType;
use App\Handler\ChartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class DashboardController extends AbstractController
{
    #[Route(['/', '/dashboard'], name: 'app_dashboard')]
    public function index(Request $request): Response
    {
        $number = 6;

        $control_form = $this->createForm(ControlType::class);
        $control_form->handleRequest($request);

        $chart = new ChartHandler();

        if ($control_form->isSubmitted()) {
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
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
            'number' => $number,
        ]);
    }
}
