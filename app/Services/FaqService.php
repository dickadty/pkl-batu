<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    public function __construct(
        protected Faq $faq
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->faq
            ->newQuery()
            ->orderByDesc('id')
            ->get();
    }

    public function getActiveForPublic(): Collection
    {
        return $this->faq
            ->newQuery()
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();
    }

    public function findById(int $id): Faq
    {
        return $this->faq
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(array $data): Faq
    {
        return $this->faq
            ->newQuery()
            ->create([
                'pertanyaan' => $data['pertanyaan'],
                'jawaban' => $data['jawaban'],
                'tanggal' => time(),
                'status' => $data['status'] ?? 1,
            ]);
    }

    public function update(int $id, array $data): Faq
    {
        $faq = $this->findById($id);

        $faq->update([
            'pertanyaan' => $data['pertanyaan'],
            'jawaban' => $data['jawaban'],
            'status' => $data['status'] ?? 0,
        ]);

        return $faq;
    }

    public function delete(int $id): void
    {
        $faq = $this->findById($id);

        $faq->delete();
    }
}
