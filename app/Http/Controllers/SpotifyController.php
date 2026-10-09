<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    // 1. Redirect user ke halaman login Spotify
    public function redirectToSpotify()
    {
        $query = http_build_query([
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'scope' => 'streaming user-read-email user-read-private user-modify-playback-state',
            'show_dialog' => 'true'
        ]);

        return redirect('https://accounts.spotify.com/authorize?' . $query);
    }

    // 2. Tangkap callback & tukar 'code' dengan Access Token
    public function handleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('playlist')->with('error', 'Gagal menghubungkan akun Spotify.');
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SPOTIFY_CLIENT_ID') . ':' . env('SPOTIFY_CLIENT_SECRET'))
        ])->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Simpan access_token ke session atau database user
            session([
                'spotify_access_token' => $data['access_token'],
                'spotify_refresh_token' => $data['refresh_token'] ?? null,
            ]);

            return redirect()->route('playlist')->with('success', 'Berhasil terhubung dengan Spotify!');
        }

        return redirect()->route('playlist')->with('error', 'Gagal mendapatkan token Spotify.');
    }
}