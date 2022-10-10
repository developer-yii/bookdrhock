<?php

namespace App\Http\Controllers\Site;

use App\Model\PollCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Poll;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $latest_polls = Poll::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $popular_polls = Poll::query()
            ->orderBy('created_at', 'desc')
            ->where('popular_tag', true)
            ->get();

        $categories = PollCategory::all();
        $categorywise_polls = [];

        foreach ($categories as $category) {
            $polls = Poll::query()
                ->orderBy('created_at', 'desc')
                ->where('category', $category->id)
                ->get();

            if (isset($polls) && !empty($polls) && count($polls) > 0) {
                $categorywise_polls[$category->slug] = $polls;
            }
        }

        return view('index', compact('latest_polls', 'popular_polls', 'categorywise_polls'));
    }

    public function getCategoryView($slug)
    {
        $polls = [];
        if (isset($slug) && !empty(isset($slug)) && $slug  == "latest") {
            $category = 'Latest';
            $polls = Poll::query()
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif (isset($slug) && !empty(isset($slug)) && $slug  == "popular") {
            $category = 'Popular';
            $polls = Poll::query()
                ->orderBy('created_at', 'desc')
                ->where('popular_tag', true)
                ->get();
        } else {
            $category = PollCategory::query()
                ->where('slug', $slug)
                ->first();

            if (isset($category) && !empty($category)) {
                $polls = Poll::query()
                    ->orderBy('created_at', 'desc')
                    ->where('category', $category->id)
                    ->get();
            } else {
                return abort(404);
            }
        }

        return view('site.category', compact('polls', 'category'));
    }

    public function about()
    {
        return view('site.about');
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function privacyPolicy()
    {
        return view('site.privacypolicy');
    }
}
