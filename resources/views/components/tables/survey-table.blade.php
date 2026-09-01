@props(['surveys', 'stats' => []])


@php

    $isPaginated = $surveys instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $surveys->getCollection() : collect($surveys);

    $rowIds = $currentItems->pluck('id')->filter()->map(fn($id) => (string) $id)->unique()->values()->all();

    $firstNumber = $isPaginated ? $surveys->firstItem() ?? 1 : 1;

@endphp



<x-tables.basic-tables.basic-tables-two title="Data Survey"
    description="Kelola hasil penilaian masyarakat terhadap pelayanan PPID Kota Batu." :row-ids="$rowIds"
    :paginator="$isPaginated ? $surveys : null" :selectable="false" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" min-width="min-w-[950px]">



    {{-- HEADER ACTION --}}

    <x-slot:headerActions>

        <div class="flex items-center gap-2">


            <span
                class="
inline-flex
items-center
rounded-lg
bg-gray-100
px-3
py-2
text-xs
font-medium
text-gray-600
dark:bg-gray-700
dark:text-gray-300
">

                Hari ini:

                <strong class="ml-1">
                    {{ number_format($stats['today'] ?? 0, 0, ',', '.') }}
                </strong>

            </span>



            <span
                class="
inline-flex
items-center
rounded-lg
bg-gray-100
px-3
py-2
text-xs
font-medium
text-gray-600
dark:bg-gray-700
dark:text-gray-300
">

                Bulan ini:

                <strong class="ml-1">
                    {{ number_format($stats['month'] ?? 0, 0, ',', '.') }}
                </strong>

            </span>


        </div>


    </x-slot:headerActions>





    {{-- HEADER TABLE --}}

    <x-slot:head>


        <th class="w-16 px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            No
        </th>


        <th class="min-w-[200px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            Responden
        </th>


        <th class="min-w-[180px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            Layanan
        </th>


        <th class="w-[130px] px-4 py-3.5 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
            Rating
        </th>


        <th class="min-w-[260px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            Kritik & Saran
        </th>


        <th class="w-[160px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            Tanggal
        </th>


        <th class="w-[100px] px-4 py-3.5 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
            Action
        </th>


    </x-slot:head>






    {{-- BODY --}}

    @forelse($currentItems as $index=>$survey)


        @php
            $rowNumber = $firstNumber + $index;
        @endphp



        <tr class="
group/row
transition-colors
duration-200
hover:bg-gray-50
dark:hover:bg-white/[0.03]
">



            {{-- NO --}}

            <td class="px-4 py-4 sm:px-6">

                <span
                    class="
inline-flex
h-8
min-w-8
items-center
justify-center
rounded-lg
bg-gray-100
px-2
text-xs
font-semibold
text-gray-600
dark:bg-gray-800
dark:text-gray-300
">

                    {{ $rowNumber }}

                </span>

            </td>






            {{-- RESPONDEN --}}

            <td class="px-4 py-4 sm:px-6">


                <div class="flex items-center gap-3">


                    <div
                        class="
flex
h-9
w-9
shrink-0
items-center
justify-center
rounded-full
bg-blue-100
text-sm
font-bold
text-blue-600
dark:bg-blue-500/15
dark:text-blue-400
">

                        {{ strtoupper(mb_substr($survey->name ?: 'A', 0, 1)) }}

                    </div>



                    <div class="min-w-0">


                        <p class="
truncate
text-sm
font-semibold
text-gray-800
dark:text-gray-200
">

                            {{ $survey->name ?: 'Anonim' }}

                        </p>


                        <p class="text-xs text-gray-400">

                            ID:
                            {{ $survey->id }}

                        </p>


                    </div>


                </div>


            </td>







            {{-- LAYANAN --}}

            <td class="px-4 py-4 sm:px-6">


                <p class="
text-sm
text-gray-600
dark:text-gray-300
">

                    {{ $survey->service ?: '-' }}

                </p>


            </td>







            {{-- RATING --}}

            <td class="px-4 py-4 text-center sm:px-6">


                <div class="flex justify-center gap-0.5">


                    @for ($star = 1; $star <= 5; $star++)
                        <i
                            class="
ri-star-fill
text-sm
{{ $star <= (int) $survey->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-700' }}
">
                        </i>
                    @endfor


                </div>


                <p class="
mt-1
text-xs
font-semibold
text-gray-500
">

                    {{ $survey->rating }}/5

                </p>


            </td>








            {{-- KRITIK --}}

            <td class="px-4 py-4 sm:px-6">


                @if ($survey->message)
                    <p class="
line-clamp-2
text-sm
leading-6
text-gray-600
dark:text-gray-300
">

                        {{ $survey->message }}

                    </p>
                @else
                    <span
                        class="
inline-flex
items-center
rounded-full
bg-green-50
px-2.5
py-1
text-xs
font-medium
text-green-700
dark:bg-green-500/15
dark:text-green-400
">

                        Tanpa kritik

                    </span>
                @endif


            </td>








            {{-- TANGGAL --}}

            <td class="px-4 py-4 sm:px-6">


                <div>


                    <p class="
text-sm
font-medium
text-gray-700
dark:text-gray-300
">

                        {{ optional($survey->created_at)->format('d M Y') }}

                    </p>


                    <p class="text-xs text-gray-400">

                        {{ optional($survey->created_at)->format('H:i') }}

                    </p>


                </div>


            </td>







            {{-- ACTION --}}

            <td class="px-4 py-4 text-center sm:px-6">


                <x-tables.row-actions :delete-url="route('admin.survey.destroy', $survey->id)" :delete-label="'Hapus survey ' . ($survey->name ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus hasil survey ini?" />


            </td>






        </tr>



    @empty



        <tr>

            <td colspan="7" class="px-6 py-12 text-center">


                <div
                    class="
mx-auto
flex
h-14
w-14
items-center
justify-center
rounded-full
bg-gray-100
text-gray-400
dark:bg-gray-700
">


                    <i class="ri-survey-line text-2xl"></i>


                </div>



                <h3 class="
mt-3
text-base
font-semibold
text-gray-800
dark:text-white/90
">

                    Belum ada data survey

                </h3>


                <p class="mt-1 text-sm text-gray-500">

                    Penilaian masyarakat akan tampil pada halaman ini.

                </p>


            </td>

        </tr>



    @endforelse



</x-tables.basic-tables.basic-tables-two>
