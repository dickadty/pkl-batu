<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Survey::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($query) use ($search): void {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('service', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('service')) {
            $query->where(
                'service',
                $request->input('service')
            );
        }

        if ($request->filled('rating')) {
            $query->where(
                'rating',
                (int) $request->input('rating')
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->input('date_from')
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->input('date_to')
            );
        }

        $surveys = $query
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $totalResponses = Survey::query()
            ->count();

        $averageRating = round(
            (float) (
                Survey::query()->avg('rating') ?? 0
            ),
            1
        );

        $fiveStarCount = Survey::query()
            ->where('rating', 5)
            ->count();

        $feedbackCount = Survey::query()
            ->whereBetween('rating', [1, 4])
            ->whereNotNull('message')
            ->where('message', '!=', '')
            ->count();

        $ratingRaw = Survey::query()
            ->selectRaw('rating, COUNT(*) AS total')
            ->whereBetween('rating', [1, 5])
            ->groupBy('rating')
            ->pluck(
                'total',
                'rating'
            );

        $ratingDistribution = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $count = (int) $ratingRaw->get(
                $rating,
                0
            );

            $percentage = $totalResponses > 0
                ? round(
                    ($count / $totalResponses) * 100,
                    1
                )
                : 0;

            $ratingDistribution[$rating] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        $serviceStats = Survey::query()
            ->select(
                'service',
                DB::raw('COUNT(*) AS total'),
                DB::raw('AVG(rating) AS average_rating')
            )
            ->whereNotNull('service')
            ->groupBy('service')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($totalResponses) {
                return [
                    'service' => $item->service,
                    'total' => (int) $item->total,
                    'average' => round(
                        (float) $item->average_rating,
                        1
                    ),
                    'percentage' => $totalResponses > 0
                        ? round(
                            (
                                (int) $item->total /
                                $totalResponses
                            ) * 100,
                            1
                        )
                        : 0,
                ];
            });

        $services = Survey::query()
            ->whereNotNull('service')
            ->where('service', '!=', '')
            ->distinct()
            ->orderBy('service')
            ->pluck('service');

        $todayResponses = Survey::query()
            ->whereDate(
                'created_at',
                today()
            )
            ->count();

        $monthResponses = Survey::query()
            ->whereYear(
                'created_at',
                now()->year
            )
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->count();

        return view(
            'pages.admin.survey.index',
            [
                'surveys' => $surveys,

                'stats' => [
                    'total' => $totalResponses,
                    'average' => $averageRating,
                    'five_star' => $fiveStarCount,
                    'feedback' => $feedbackCount,
                    'today' => $todayResponses,
                    'month' => $monthResponses,
                ],

                'ratingDistribution' => $ratingDistribution,
                'serviceStats' => $serviceStats,
                'services' => $services,
            ]
        );
    }

    public function destroy(
        Survey $survey
    ): RedirectResponse {
        $survey->delete();

        return redirect()
            ->route('admin.survey.index')
            ->with(
                'success',
                'Data survey berhasil dihapus.'
            );
    }
}
