@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
    <h2>Profil Saya</h2>
    <table class="table w-50">
        <tr><th>Nama</th><td>{{ Auth::user()->name }}</td></tr>
        <tr><th>Email</th><td>{{ Auth::user()->email }}</td></tr>
        <tr><th>Terdaftar</th><td>{{ Auth::user()->created_at->format('d M Y') }}</td></tr>
    </table>
@endsection