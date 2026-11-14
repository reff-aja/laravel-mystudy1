<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser as PdfParser;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai-chat');
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);

        $message = trim($validated['message'] ?? '');
        $file = $request->file('file');

        if ($message === '' && !$file) {
            return response()->json([
                'reply' => 'Tulis pesan atau lampirkan gambar/PDF terlebih dahulu.',
            ], 422);
        }

        $systemPrompt = 'Kamu adalah asisten AI pintar dan gaul dari platform MyStudy. DIIZINKAN menjawab SEMUA pertanyaan. ATURAN PENTING: 1. JANGAN menggunakan format LaTeX (seperti \\frac, \\sqrt, \\[ , \\]) untuk matematika, gunakan teks biasa (contoh: a pangkat 2). 2. JIKA pengguna menginput teks atau bertanya dalam bahasa asing (Arab, Mandarin, Inggris, Jepang, dll), kamu WAJIB menjawab/merespons dalam bahasa asing tersebut DAN menyertakan terjemahan atau arti lengkapnya dalam Bahasa Indonesia di bawahnya agar pengguna paham. Jawablah dengan akurat, suportif, dan ringkas. Jika pengguna mengirim file, dasarkan jawaban pada isi file yang berhasil dibaca dan jangan mengarang isi yang tidak terlihat.';

        // Jalur khusus gambar: gunakan model Groq yang mendukung input vision.
        if ($file && str_starts_with($file->getMimeType(), 'image/')) {
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();

            $userContent = [
                [
                    'type' => 'text',
                    'text' => $message !== ''
                        ? $message
                        : 'Tolong jelaskan dan analisis isi gambar ini.',
                ],
                [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => "data:{$mimeType};base64,{$imageData}",
                    ],
                ],
            ];

            $response = Http::withoutVerifying()
                ->withToken(env('GROQ_API_KEY'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'qwen/qwen3.8-27b',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userContent],
                    ],
                    'max_tokens' => 1000,
                ]);
        } else {
            // Jalur teks dan PDF tetap menggunakan model GPT-OSS yang sudah dipakai.
            if ($file && $file->getMimeType() === 'application/pdf') {
                try {
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($file->getRealPath());
                    $pdfText = trim($pdf->getText());
                } catch (\Throwable $e) {
                    return response()->json([
                        'reply' => 'PDF belum berhasil dibaca. Pastikan file tidak rusak dan PDF berisi teks yang dapat diseleksi.',
                    ], 422);
                }

                if ($pdfText === '') {
                    return response()->json([
                        'reply' => 'Teks tidak ditemukan di PDF ini. Jika PDF berupa hasil scan/foto, unggah halaman sebagai gambar JPG atau PNG.',
                    ], 422);
                }

                // Batasi panjang teks agar ukuran request dan penggunaan token tetap terkendali.
                $pdfText = mb_substr($pdfText, 0, 30000);
                $message = ($message !== '' ? $message . "\n\n" : 'Tolong analisis dokumen PDF berikut.\n\n')
                    . "Isi PDF:\n" . $pdfText;
            }

            $response = Http::withoutVerifying()
                ->withToken(env('GROQ_API_KEY'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-120b', // Model Groq yang sudah digunakan MyStudy
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message],
                    ],
                ]);
        }

        if ($response->successful()) {
            return response()->json([
                'reply' => $response->json('choices.0.message.content') ?? 'AI belum memberikan jawaban.',
            ]);
        }

        // Pesan error dibuat ringkas agar detail internal/API tidak ditampilkan ke pengguna.
        return response()->json([
            'reply' => 'Groq gagal memproses permintaan. Silakan coba lagi. (HTTP ' . $response->status() . ')',
        ], 500);
    }
}
