<?php

namespace App\Controller;

use App\Service\BigQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BigQueryController extends AbstractController
{
    /**
     * @Route("/api/bigquery_data", name="get_data", methods={"GET"})
     */
    public function getData(BigQueryService $bigQueryService): JsonResponse
    {
        try {
            // Consulta SQL
            $query = "SELECT title, body, tags, creation_date
                    FROM `bigquery-public-data.stackoverflow.posts_questions`
                    ORDER BY creation_date DESC
                    LIMIT 10";
            // Ejecuta la consulta con el método del BigQueryService
            $results = $bigQueryService->runQuery($query);

            // Devuelve los resultados en formato JSON con estado HTTP 200
            return new JsonResponse($results, JsonResponse::HTTP_OK);
        } catch (\RuntimeException $e) {
            // En caso de error, devuelve mensaje con estado HTTP 500
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/bigquery_tag/{tag}", name="get_data_by_tag", methods={"GET"})
     */
    public function getDataByTag(BigQueryService $bigQueryService, string $tag): JsonResponse
    {
        try {
            // Consulta SQL. Se usa 'sprintf' para evitar cualquier tipo de ataque de inyección SQL
            $query = sprintf(
                "SELECT title, body, tags, creation_date
                 FROM `bigquery-public-data.stackoverflow.posts_questions`
                 WHERE tags LIKE '%%%s%%'
                 ORDER BY creation_date DESC
                 LIMIT 10",
                $tag
            );
            // Ejecuta la consulta con el método del BigQueryService
            $results = $bigQueryService->runQuery($query);

            // Devuelve los resultados en formato JSON con estado HTTP 200
            return new JsonResponse($results, JsonResponse::HTTP_OK);
        } catch (\RuntimeException $e) {
            // En caso de error, devuelve mensaje con estado HTTP 500
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}