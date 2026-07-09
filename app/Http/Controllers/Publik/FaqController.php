<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\FaqService;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $faqService
    ) {}

    public function index()
    {
        $faq = $this->faqService->getActiveForPublic();

        return view('pages.public.faq.index', compact('faq'));
    }
}
