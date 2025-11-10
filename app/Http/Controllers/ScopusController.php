<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScopusController extends Controller
{
    //
    protected $apiKey = '0ddd0380179f5606640e22dd336ac6c5'; // 🔑 เปลี่ยนตรงนี้

    public function getAuthorByName($firstname, $lastname)
    {
        $url = 'https://api.elsevier.com/content/search/author';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($url, [
            'query' => "authlast($lastname)+and+authfirst($firstname)",
            'count' => 1
        ]);

        if ($response->successful()) {
            $result = $response->json();
            return response()->json($result);
        } else {
            return response()->json(['error' => 'ไม่พบข้อมูลนักวิจัย'], 400);
        }
    }

    public function getAuthorDetails($authorId)
    {
        $url = "https://api.elsevier.com/content/author/author_id/$authorId";

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'ไม่สามารถดึงข้อมูล Author ID ได้'], 400);
        }
    }

    public function getPublications($authorId)
    {
        $url = "https://api.elsevier.com/content/search/scopus";

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($url, [
            'query' => "au-id($authorId)",
            'count' => 5
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'ไม่สามารถดึงรายการบทความได้'], 400);
        }
    }

    public function showAuthor()
    {
        $authorId = '23390580400';

        // ดึงข้อมูล Author
        $authorUrl = "https://api.elsevier.com/content/author/author_id/{$authorId}";
        $authorRes = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($authorUrl);

        $hIndexUrl = "https://api.elsevier.com/content/author/author_id/{$authorId}?view=ENHANCED";
        $hIndexss = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($hIndexUrl);

        

        $hIndexs = $hIndexss['author-retrieval-response'][0];
        $hIndex = $hIndexs['h-index'] ?? 'N/A';
       //dd($hIndexss->json());


        // ดึงผลงานล่าสุด
        $pubUrl = "https://api.elsevier.com/content/search/scopus";
        $pubRes = Http::withHeaders([
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ])->get($pubUrl, [
            'query' => "au-id({$authorId})",
            'count' => 200
            // 'start' => 0
        ]);

        if ($authorRes->failed() || $pubRes->failed()) {
            return view('scopus', ['error' => 'ไม่สามารถดึงข้อมูลจาก Scopus API ได้']);
        }

        $author = $authorRes['author-retrieval-response'][0];
        $articles = $pubRes['search-results']['entry'] ?? [];
        $articles = array_filter($articles, function ($article) {
            $date = $article['prism:coverDate'] ?? null;
            if ($date) {
                $year = (int) substr($date, 0, 4);
                return $year >= 2004 && $year <= 2005;
            }
            return false;
        });
        //dd($articles);
        return view('scopus', [
            'fullName' => ($author['author-profile']['preferred-name']['given-name'] ?? '') . ' ' . ($author['author-profile']['preferred-name']['surname'] ?? ''),
            'affiliation' => $author['affiliation-current']['affiliation-name'] ?? 'N/A',
            'publicationCount' => $author['coredata']['document-count'] ?? '0',
            'hIndex' => $hIndex ?? '0',
            'articles' => $articles
        ]);
    }
}
