<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeAdminView extends Command
{
    protected $signature = 'make:admin-view {name}';

    protected $description = 'Membuat halaman CRUD admin';

    public function handle()
    {
        $name = $this->argument('name');

        $folder = Str::snake($name);

        $title = Str::headline($name);

        $path = resource_path(
            "views/pages/admin/{$folder}"
        );

        if (!File::exists($path)) {
            File::makeDirectory(
                $path,
                0755,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        $index = <<<BLADE
@extends('layouts.admin.app')

@section('title', 'Manajemen {$title}')

@section('content')

<div class="panel-card">

    <div class="panel-card-header flex justify-between items-center">

        <span>Manajemen {$title}</span>

        <a href="#"
           class="btn btn-primary">

            Tambah {$title}

        </a>

    </div>

    <div class="panel-card-body">

        <table class="table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th width="180">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                {{-- Data --}}

            </tbody>

        </table>

    </div>

</div>

@endsection
BLADE;


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        $create = <<<BLADE
@extends('layouts.admin.app')

@section('title', 'Tambah {$title}')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">

        Tambah {$title}

    </div>

    <div class="panel-card-body">

        <form action="#" method="POST">

            @csrf

            {{-- Form input di sini --}}

            <div class="flex gap-2 mt-4">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Simpan

                </button>

                <a
                    href="#"
                    class="btn btn-secondary">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
BLADE;


        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        */

        $edit = <<<BLADE
@extends('layouts.admin.app')

@section('title', 'Edit {$title}')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">

        Edit {$title}

    </div>

    <div class="panel-card-body">

        <form action="#" method="POST">

            @csrf

            @method('PUT')

            {{-- Form input di sini --}}

            <div class="flex gap-2 mt-4">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Update

                </button>

                <a
                    href="#"
                    class="btn btn-secondary">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
BLADE;


        /*
        |--------------------------------------------------------------------------
        | CREATE FILE
        |--------------------------------------------------------------------------
        */

        File::put(
            "{$path}/index.blade.php",
            $index
        );

        File::put(
            "{$path}/create.blade.php",
            $create
        );

        File::put(
            "{$path}/edit.blade.php",
            $edit
        );


        $this->info(
            "Admin CRUD view '{$folder}' berhasil dibuat!"
        );

        $this->newLine();

        $this->line(
            "✓ index.blade.php"
        );

        $this->line(
            "✓ create.blade.php"
        );

        $this->line(
            "✓ edit.blade.php"
        );

        return Command::SUCCESS;
    }
}