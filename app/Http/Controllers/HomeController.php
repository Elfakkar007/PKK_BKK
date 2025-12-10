<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Highlight;
use App\Models\AboutContent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $highlights = Highlight::where('is_active', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $latestNews = Post::published()
            ->news()
            ->latest('published_at')
            ->take(3)
            ->get();

        $activeVacancies = JobVacancy::active()
            ->with('company')
            ->latest()
            ->take(6)
            ->get();

        $companiesCount = Company::whereHas('user', function($q) {
            $q->where('status', 'approved');
        })->count();

        $vacanciesCount = JobVacancy::active()->count();

        return view('home', compact(
            'highlights',
            'latestNews',
            'activeVacancies',
            'companiesCount',
            'vacanciesCount'
        ));
    }

    public function information(Request $request)
    {
        $category = $request->get('category', 'news');
        
        $posts = Post::published()
            ->where('category', $category)
            ->latest('published_at')
            ->paginate(9);

        return view('information.index', compact('posts', 'category'));
    }

    public function informationShow($slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementViews();

        $relatedPosts = Post::published()
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('information.show', compact('post', 'relatedPosts'));
    }

    public function companies(Request $request)
    {
        $search = $request->get('search');
        $sector = $request->get('sector');

        $companies = Company::whereHas('user', function($q) {
                $q->where('status', 'approved');
            })
            ->when($search, function($q, $search) {
                $q->where('name', 'ILIKE', "%{$search}%");
            })
            ->when($sector, function($q, $sector) {
                $q->where('industry_sector', $sector);
            })
            ->with('user')
            ->paginate(12);

        $sectors = Company::whereHas('user', function($q) {
                $q->where('status', 'approved');
            })
            ->distinct()
            ->pluck('industry_sector');

        return view('companies.index', compact('companies', 'sectors'));
    }

    public function companyShow($id)
    {
        $company = Company::whereHas('user', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['activeVacancies', 'user'])
            ->findOrFail($id);

        return view('companies.show', compact('company'));
    }

    public function vacancies(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = $request->get('search');
        $location = $request->get('location');

        $vacancies = JobVacancy::active()
            ->with('company')
            ->when($type !== 'all', function($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($search, function($q, $search) {
                $q->where('title', 'ILIKE', "%{$search}%");
            })
            ->when($location, function($q, $location) {
                $q->where('location', 'ILIKE', "%{$location}%");
            })
            ->latest()
            ->paginate(12);

        return view('vacancies.index', compact('vacancies', 'type'));
    }

    public function vacancyShow($id)
    {
        $vacancy = JobVacancy::active()
            ->with('company.user')
            ->findOrFail($id);

        $relatedVacancies = JobVacancy::active()
            ->where('company_id', $vacancy->company_id)
            ->where('id', '!=', $vacancy->id)
            ->take(3)
            ->get();

        return view('vacancies.show', compact('vacancy', 'relatedVacancies'));
    }

    public function about()
    {
        $about = AboutContent::first();
        
        if (!$about) {
            $about = new AboutContent();
        }

        return view('about', compact('about'));
    }
}