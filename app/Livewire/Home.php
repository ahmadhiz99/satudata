<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\DatasetUpload;
use App\Models\Organization;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $totalDatasets     = DatasetUpload::count();
        $totalOrganizations = Organization::where('is_active', true)->count();
        $totalCategories   = Category::where('is_active', true)->count();
        $totalViews        = DatasetUpload::sum('view_count'); // sesuaikan jika kolom berbeda

        $categories = Category::where('is_active', true)
            ->withCount('datasetUploads')
            ->get();

        $trending = DatasetUpload::with('organization')
            ->orderBy('view_count', 'desc')
            ->limit(6)
            ->get();

        return view('livewire.home', compact(
            'totalDatasets',
            'totalOrganizations',
            'totalCategories',
            'totalViews',
            'categories',
            'trending',
        ))->layout('components.layouts.public', ['title' => 'Beranda']);
    }
}