<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai-chat');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $apiKey = config('services.groq.key') ?: env('GROQ_API_KEY');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => "⚠️ **GROQ_API_KEY belum diatur!**\n\nSilakan tambahkan API key Groq di file `.env`:\n```env\nGROQ_API_KEY=gsk_xxxxxxxxxxxx\n```\nAnda bisa mendapatkan API key gratis di [console.groq.com/keys](https://console.groq.com/keys)."
            ], 400);
        }

        $model = config('services.groq.model', 'openai/gpt-oss-120b');

        // Kita tambahkan withoutVerifying() untuk mengatasi masalah SSL di localhost Windows
        $response = Http::withoutVerifying()
            ->withToken($apiKey)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'Kamu adalah asisten AI pintar dan gaul dari platform MyStudy. DIIZINKAN menjawab SEMUA pertanyaan. ATURAN PENTING: 1. JANGAN menggunakan format LaTeX (seperti \frac, \sqrt, \[, \]) untuk matematika, gunakan teks biasa (contoh: a pangkat 2). 2. JIKA pengguna menginput teks atau bertanya dalam bahasa asing (Arab, Mandarin, Inggris, Jepang, dll), kamu WAJIB menjawab/merespons dalam bahasa asing tersebut DAN menyertakan terjemahan atau arti lengkapnya dalam Bahasa Indonesia di bawahnya agar pengguna paham. Jawablah dengan akurat, suportif, dan ringkas.'
                    ],
                    [
                        'role' => 'user', 
                        'content' => $request->message
                    ],
                ],
            ]);

        if ($response->successful()) {
            return response()->json([
                'reply' => $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respon dari AI.'
            ]);
        }

        $errorMessage = $response->json()['error']['message'] ?? $response->body();

        return response()->json([
            'reply' => 'Error dari Groq: ' . $errorMessage
        ], 500);
    }
}   