<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportCsvExportService
{
    /**
     * Generate a CSV StreamedResponse.
     *
     * @param  array  $headers  CSV column headers
     * @param  callable|LazyCollection|Collection|iterable  $rows
     * @param  array  $columns  Specific columns to extract from each row if rows is an object collection
     */
    public function download(
        string $filename,
        array $headers,
        $rows,
        array $columns = []
    ): StreamedResponse {
        $response = new StreamedResponse(function () use ($headers, $rows, $columns) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write CSV header
            fputcsv($handle, $headers);

            // Write CSV rows
            foreach ($rows as $row) {
                if (! empty($columns) && is_object($row)) {
                    $data = [];
                    foreach ($columns as $col) {
                        $data[] = data_get($row, $col) ?? '';
                    }
                    fputcsv($handle, $data);
                } elseif (is_array($row)) {
                    fputcsv($handle, $row);
                } elseif (is_object($row) && method_exists($row, 'toCsvRow')) {
                    fputcsv($handle, $row->toCsvRow());
                } else {
                    fputcsv($handle, (array) $row);
                }
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);

        return $response;
    }
}
