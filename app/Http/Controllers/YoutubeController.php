use Illuminate\Support\Facades\Http;

public function getYoutubeInfo($url)
{
    $apiKey = env('YOUTUBE_API_KEY');
    
    // Ambil Video ID dari URL YouTube
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
    $videoId = $matches[1] ?? null;

    if ($videoId) {
        $response = Http::get("https://www.googleapis.com/youtube/v3/videos?id={$videoId}&key={$apiKey}&part=snippet");
        if ($response->successful() && count($response->json()['items']) > 0) {
            $item = $response->json()['items'][0]['snippet'];
            return [
                'type' => 'youtube',
                'title' => $item['title'],
                'thumbnail' => $item['thumbnails']['medium']['url'],
                'embed_url' => "https://www.youtube.com/embed/{$videoId}?autoplay=1"
            ];
        }
    }

    return null;
}