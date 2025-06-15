@extends('layouts.main_all')
@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Scopus Author CV</h2>

    @isset($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @else
        <div class="card p-3 mb-4">
            <h4><strong>ชื่อ - นามสกุล:</strong> {{ $fullName }}</h4>
            <p><strong>สังกัด:</strong> {{ $affiliation }}</p>
            <p><strong>จำนวนบทความ:</strong> {{ $publicationCount }}</p>
            <p><strong>h-index:</strong> {{ $hIndex }}</p>
        </div>

        <h5>📄 รายชื่อบทความล่าสุด</h5>
        <ul class="list-group">
            @foreach ($articles as $article)
                <li class="list-group-item">
                    <strong>
                    <a href="{{ $article['link'][2]['@href'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                        {{ $article['dc:title'] ?? 'ไม่มีชื่อบทความ' }}</strong></a><br>
                    <small>ปี: {{ $article['prism:coverDate'] ?? 'ไม่ระบุ' }}</small><br>
                    <small>DOI: {{ $article['prism:doi'] ?? 'ไม่มี DOI' }}</small>
                </li>
            @endforeach
        </ul>
    @endisset
</div>
@endsection