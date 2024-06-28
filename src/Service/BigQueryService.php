<?php

namespace App\Service;

use Google\Cloud\BigQuery\BigQueryClient;

class BigQueryService
{
    // Atributo para la instacia de BigQuery
    private $bigQueryClient;

    /**
     * Constructor de BigQuery
     * 
     * @param string $projectId ID del proyecto de Google Cloud
     * @param string $keyFilePath Ruta del archivo de credenciales JSON
     */
    public function __construct(string $projectId, string $keyFilePath)
    {
        // Inicializa el cliente de BigQuery
        $this->bigQueryClient = new BigQueryClient([
            'projectId' => $projectId,
            'keyFilePath' => $keyFilePath,
        ]);
    }

    /**
    * Ejecuta SQL en BigQuery y devuelve los resultados
    *
    * @param string $queryString La consulta SQL a ejecutar
    * @return array Convertimos los resultados en formato array
    */

    public function runQuery(string $queryString): array
    {
        $queryJob = $this->bigQueryClient->query($queryString);
        $results = $this->bigQueryClient->runQuery($queryJob);

        return iterator_to_array($results->rows());
    }
}