<?php

namespace App\Service;

class CsvService
{
    public function generateCsvResponse(array $data, string $filename): \Symfony\Component\HttpFoundation\Response
    {
        $handle = fopen('php://temp', 'r+');

        // UTF-8 BOM pour Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // En-têtes
        fputcsv($handle, array_keys($data[0]), ';');

        // Données
        foreach ($data as $row) {
            fputcsv($handle, $row, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        $response = new \Symfony\Component\HttpFoundation\Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }
}