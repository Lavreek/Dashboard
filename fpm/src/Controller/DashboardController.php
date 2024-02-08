<?php

namespace App\Controller;

use App\Handler\ChartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(['/', '/dashboard'], name: 'app_dashboard')]
    public function index(): Response
    {
        $chart = new ChartHandler();

        $chart->setChartData(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
                [
                    'label' => 'My Second dataset',
                    'backgroundColor' => 'rgb(255, 199, 13)',
                    'borderColor' => 'rgb(255, 199, 13)',
                    'data' => [10, 5, 2, 20, 30, 45, 8],
                ],
            ]
        );

        return $this->render('dashboard/index.html.twig', [
            'chart' => $chart->getChart()
        ]);
    }
}
