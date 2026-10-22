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

        // Kita tambahkan withoutVerifying() untuk mengatasi masalah SSL di localhost Windows
        $response = Http::withoutVerifying()
            ->withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'openai/gpt-oss-120b', // Model Groq yang kita gunakan
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
                'reply' => $response->json()['choices'][0]['message']['content']
            ]);
        }

        // KITA UBAH BAGIAN ERROR-NYA: Biar Groq ngasih tau masalah aslinya apa
        return response()->json([
            'reply' => 'Error Asli dari Groq: ' . $response->body()
        ], 500);
    }
}   