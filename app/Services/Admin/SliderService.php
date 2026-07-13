<?php

namespace App\Services\Admin;

use App\Models\Slider;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class SliderService
{
    public function __construct(
        protected Slider $slider,
        protected FilesystemFactory $storage
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->slider
            ->newQuery()
            ->orderByDesc('id')
            ->get();
    }

    public function findById(int $id): Slider
    {
        return $this->slider
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(array $data, UploadedFile $banner): Slider
    {
        $data['banner'] = $this->storeBanner($banner);
        $data['tanggal'] = time();

        return $this->slider
            ->newQuery()
            ->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $banner = null): Slider
    {
        $slider = $this->findById($id);

        if ($banner) {
            $this->deleteFile($slider->banner);

            $data['banner'] = $this->storeBanner($banner);
        }

        $slider->update($data);

        return $slider;
    }

    public function delete(int $id): void
    {
        $slider = $this->findById($id);

        $this->deleteFile($slider->banner);

        $slider->delete();
    }

    private function storeBanner(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = time() . '_' .
            str($originalName)->slug()->toString() .
            '.' .
            $file->getClientOriginalExtension();

        return $file->storeAs('slider', $filename, 'public');
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = $this->storage->disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}
