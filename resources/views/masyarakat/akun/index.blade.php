@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('sidebar-menu')
    @include('layouts.sidebar-masyarakat')
@endsection

@section('page-title', 'Kelola Akun')
@section('page-subtitle', 'Pengaturan profil dan keamanan akun')

@section('content')
    @include('shared.akun')
@endsection