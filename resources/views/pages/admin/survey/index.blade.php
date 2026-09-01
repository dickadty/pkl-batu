@extends('layouts.admin.app')

@section('title', 'Dashboard Survey')

@section('content')

    <div class="space-y-6">


        {{-- ============================================================
        HEADER
    ============================================================= --}}

        <x-admin.page-header title="Dashboard Survey"
            description="Pantau tingkat kepuasan masyarakat, distribusi rating, serta kritik dan saran terhadap pelayanan PPID Kota Batu."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pelayanan',
                ],
                [
                    'label' => 'Survey',
                ],
            ]" />


        {{-- ============================================================
        FLASH MESSAGE
    ============================================================= --}}

        <x-ui.flash-messages />



        {{-- ============================================================
        STAT CARD
    ============================================================= --}}

        <x-admin.survey-stat-card :stats="$stats" />



        {{-- ============================================================
        CHART SECTION
    ============================================================= --}}

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            <x-admin.rating-distribution :ratingDistribution="$ratingDistribution" :average="$stats['average'] ?? 0" />



            <x-admin.service-statistic :serviceStats="$serviceStats" />


        </div>




        {{-- ============================================================
        DATA SURVEY
    ============================================================= --}}

        <x-tables.survey-table :surveys="$surveys" :stats="$stats" :services="$services" />


    </div>


@endsection
