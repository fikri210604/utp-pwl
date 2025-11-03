<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidPdf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('Berkas tidak valid.');
            return;
        }

        // Quick header check for PDF magic bytes: %PDF-
        try {
            $stream = fopen($value->getRealPath(), 'rb');
            if ($stream === false) {
                $fail('Tidak dapat membaca berkas PDF.');
                return;
            }
            $header = fread($stream, 5) ?: '';
            fclose($stream);

            if ($header !== '%PDF-') {
                $fail('Berkas harus berupa PDF yang valid.');
                return;
            }
        } catch (\Throwable $e) {
            $fail('Terjadi kesalahan saat memeriksa PDF.');
        }
    }
}

