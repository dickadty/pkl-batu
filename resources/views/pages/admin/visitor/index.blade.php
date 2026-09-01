@extends('layouts.admin.app')


@section('title', 'Dashboard Visitor')


@section('content')


    <div class="space-y-6">


        <x-admin.page-header title="Dashboard Statistik Kunjungan"
            description="Monitoring aktivitas pengunjung website PPID Kota Batu." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
            
                [
                    'label' => 'Statistik Kunjungan',
                ],
            ]" />



        <x-ui.flash-messages />



        <x-admin.visitor-stat-card :stats="$stats" />



        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            <x-admin.visitor-chart :visitorChart="$visitorChart" />


            <x-admin.popular-pages :popularPages="$popularPages" />


        </div>


    </div>


@endsection
