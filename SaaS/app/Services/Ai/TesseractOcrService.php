<?php

namespace App\Services\Ai;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOcrService
{
    /**
     * Extract text from a file using Tesseract OCR (for images) or a fallback PDF parser (for PDFs).
     */
    public function extractText(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $path = $file->getRealPath();
        $text = '';

        if ($mimeType === 'application/pdf') {
            Log::info('PDF document detected. Using PDF text extractor.', [
                'filename' => $file->getClientOriginalName(),
            ]);
            $text = $this->extractTextFromPdf($path);
        } else {
            Log::info('Image document detected. Using Tesseract OCR.', [
                'filename' => $file->getClientOriginalName(),
            ]);

            try {
                $tesseract = new TesseractOCR($path);
                
                $exePath = config('services.tesseract.path');
                if (filled($exePath)) {
                    $tesseract->executable($exePath);
                }
                
                $text = $tesseract->run();
            } catch (\Throwable $e) {
                Log::error('Tesseract OCR execution failed', [
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->sanitizeUtf8($text);
    }

    /**
     * Parse text-based PDF streams directly using PHP.
     */
    private function extractTextFromPdf(string $path): string
    {
        $content = @file_get_contents($path);
        if (!$content) {
            return '';
        }

        $text = '';
        
        // Find all stream objects in the PDF
        if (preg_match_all("/stream(.*?)endstream/is", $content, $matches)) {
            foreach ($matches[1] as $stream) {
                $stream = trim($stream);
                
                // Check if the stream is compressed with FlateDecode
                // Most modern PDFs compress streams. We try to decompress it.
                $data = @gzuncompress($stream);
                if (!$data) {
                    $data = @gzinflate($stream);
                }
                if (!$data) {
                    // Try removing leading/trailing spaces or newlines before decoding
                    $data = @gzuncompress(trim($stream, "\r\n "));
                }
                if (!$data) {
                    $data = $stream; // Fallback to raw stream if decompression fails
                }

                // Match strings inside parentheses (the standard text representation operator in PDF)
                // e.g. (Hello World) Tj or [ (Hello) 10 (World) ] TJ
                if (preg_match_all("/\((.*?)\)\s*(Tj|TJ)/is", $data, $textMatches)) {
                    foreach ($textMatches[1] as $match) {
                        $text .= $match . ' ';
                    }
                }
            }
        }

        // Clean up basic PDF escapes and octal values
        $text = preg_replace_callback('/\\\\([0-7]{3})/', function ($m) {
            return chr(octdec($m[1]));
        }, $text);
        
        $text = str_replace(
            ['\\(', '\\)', '\\\\', '\\n', '\\r', '\\t'],
            ['(', ')', '\\', "\n", "\r", "\t"],
            $text
        );

        // Normalize spaces and clean control characters
        $text = preg_replace('/[[:cntrl:]]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * Remove any invalid UTF-8 byte sequences to prevent JSON encoding errors.
     */
    private function sanitizeUtf8(string $text): string
    {
        // Convert encoding to UTF-8, replacing invalid byte sequences with a fallback/ignoring them
        $text = @mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        if (function_exists('iconv')) {
            $text = @iconv('UTF-8', 'UTF-8//IGNORE', $text);
        }
        
        // Remove non-printable control characters, but keep basic spaces/tabs/newlines
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        return $text;
    }
}
