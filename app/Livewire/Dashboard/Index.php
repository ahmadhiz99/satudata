<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Dataset;
use App\Models\DatasetRecord;
use App\Models\DatasetUpload;
use App\Models\Organization;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $stats = [
            'total_uploads'      => DatasetUpload::count(),
            'total_datasets'     => Dataset::count(),
            'total_records'      => DatasetRecord::count(),
            'total_organizations'=> Organization::count(),
            'total_categories'   => Category::count(),
            'total_users'        => User::count(),
            'active_orgs'        => Organization::where('is_active', true)->count(),
            'active_categories'  => Category::where('is_active', true)->count(),
        ];

        // Upload terbaru
        $recentUploads = DatasetUpload::with('organization', 'category')
            ->latest()
            ->limit(5)
            ->get();

        // Dataset per kategori
        $datasetByCategory = Category::withCount('datasets')
            ->get();

        // Dataset per OPD (top 5)
        $datasetByOrg = Organization::withCount('datasets')
            ->limit(5)
            ->get();

        // Upload per bulan (6 bulan terakhir)
        $uploadsPerMonth = DatasetUpload::selectRaw("TO_CHAR(created_at, 'Mon YYYY') as month, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("TO_CHAR(created_at, 'Mon YYYY'), DATE_TRUNC('month', created_at)")
            ->orderByRaw("DATE_TRUNC('month', created_at)")
            ->get();

        return view('livewire.dashboard.index', compact(
            'stats',
            'recentUploads',
            'datasetByCategory',
            'datasetByOrg',
            'uploadsPerMonth',
        ));
    }
}