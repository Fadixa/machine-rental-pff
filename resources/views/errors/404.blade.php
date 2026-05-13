@extends('layouts.app')
@section('title', '404 — Page introuvable')
@section('content')
<div style="text-align:center;padding:100px 20px;">
    <div style="font-size:80px;margin-bottom:16px">🔧</div>
    <h1 style="font-size:48px;font-weight:900;color:#0F1B2D">404</h1>
    <p style="color:#6b7280;font-size:1.1rem;margin-bottom:32px">
        Cette page n'existe pas ou a été déplacée.
    </p>
    <a href="/" style="background:#F59E0B;color:#0F1B2D;padding:12px 32px;
       border-radius:10px;font-weight:700;text-decoration:none;">
        ← Retour à l'accueil
    </a>
</div>
@endsection