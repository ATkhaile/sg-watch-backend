<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use ZipArchive;

class OfficeFileExtractor
{
    /**
     * MIME types that need text extraction (not natively supported by OpenAI)
     */
    private const OFFICE_MIME_TYPES = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    /**
     * Check if file needs text extraction
     */
    public function needsExtraction(string $mimeType): bool
    {
        return in_array($mimeType, self::OFFICE_MIME_TYPES);
    }

    /**
     * Extract text content from Office file
     */
    public function extractText(string $filePath, string $mimeType): ?string
    {
        try {
            return match ($mimeType) {
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel' => $this->extractFromExcel($filePath),

                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->extractFromDocx($filePath),
                'application/msword' => $this->extractFromDoc($filePath),

                'application/vnd.openxmlformats-officedocument.presentationml.presentation' => $this->extractFromPptx($filePath),
                'application/vnd.ms-powerpoint' => $this->extractFromPpt($filePath),

                default => null,
            };
        } catch (\Exception $e) {
            \Log::warning('Failed to extract text from file: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract text from Excel files (.xlsx, .xls)
     */
    private function extractFromExcel(string $filePath): string
    {
        $spreadsheet = SpreadsheetIOFactory::load($filePath);
        $text = [];

        foreach ($spreadsheet->getAllSheets() as $sheetIndex => $sheet) {
            $sheetName = $sheet->getTitle();
            $text[] = "=== Sheet: {$sheetName} ===";

            $data = $sheet->toArray(null, true, true, true);

            foreach ($data as $rowIndex => $row) {
                $rowValues = array_filter($row, fn($cell) => $cell !== null && $cell !== '');
                if (!empty($rowValues)) {
                    $text[] = implode("\t", $rowValues);
                }
            }

            $text[] = "";
        }

        return implode("\n", $text);
    }

    /**
     * Extract text from Word .docx files
     */
    private function extractFromDocx(string $filePath): ?string
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return null;
        }

        $content = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($content === false) {
            return null;
        }

        // Remove XML tags and extract text
        $content = str_replace('</w:p>', "\n", $content);
        $content = str_replace('</w:tr>', "\n", $content);
        $content = strip_tags($content);
        $content = html_entity_decode($content, ENT_QUOTES | ENT_XML1, 'UTF-8');

        // Clean up multiple newlines
        $content = preg_replace('/\n{3,}/', "\n\n", $content);

        return trim($content);
    }

    /**
     * Extract text from legacy Word .doc files
     */
    private function extractFromDoc(string $filePath): ?string
    {
        // .doc format is binary and complex, return a simple message
        // For full support, consider using antiword or LibreOffice
        $content = file_get_contents($filePath);

        // Try to extract readable text from binary
        $text = '';
        $length = strlen($content);

        for ($i = 0; $i < $length; $i++) {
            $char = ord($content[$i]);
            if (($char >= 32 && $char <= 126) || $char === 10 || $char === 13) {
                $text .= $content[$i];
            } else {
                if (strlen($text) > 0 && substr($text, -1) !== ' ') {
                    $text .= ' ';
                }
            }
        }

        // Clean up the extracted text
        $text = preg_replace('/\s{3,}/', ' ', $text);
        $text = trim($text);

        if (strlen($text) < 50) {
            return "[.doc file - legacy format, content may not be fully extracted]";
        }

        return $text;
    }

    /**
     * Extract text from PowerPoint .pptx files
     */
    private function extractFromPptx(string $filePath): ?string
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return null;
        }

        $text = [];
        $slideIndex = 1;

        // Read all slides
        while (($content = $zip->getFromName("ppt/slides/slide{$slideIndex}.xml")) !== false) {
            $text[] = "=== Slide {$slideIndex} ===";

            // Extract text from slide XML
            $slideText = strip_tags($content);
            $slideText = html_entity_decode($slideText, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $slideText = preg_replace('/\s+/', ' ', $slideText);
            $slideText = trim($slideText);

            if (!empty($slideText)) {
                $text[] = $slideText;
            }

            $text[] = "";
            $slideIndex++;
        }

        $zip->close();

        return implode("\n", $text);
    }

    /**
     * Extract text from legacy PowerPoint .ppt files
     */
    private function extractFromPpt(string $filePath): ?string
    {
        // .ppt format is binary and complex
        return "[.ppt file - legacy format, please use .pptx for better support]";
    }
}
