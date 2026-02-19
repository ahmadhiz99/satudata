<?php

namespace App\Livewire\Datasektoral;

use App\Models\DatasetUpload;
use Livewire\Component;

class Show extends Component
{
    public DatasetUpload $dataset;

    public function mount(DatasetUpload $dataset)
    {
        $this->dataset = $dataset;

        $sessionKey = 'viewed_dataset_upload_' . $dataset->id . '_' . request()->ip();
        if (!cache()->has($sessionKey)) {
            $dataset->increment('view_count');
            cache()->put($sessionKey, true, now()->addHour());
        }
    }

    public function download()
    {
        $filePath = storage_path('app/public/' . $this->dataset->file_path);

        if (!file_exists($filePath)) {
            session()->flash('error', 'File tidak ditemukan!');
            return;
        }

        return response()->download($filePath, $this->dataset->file_name);
    }

    public function render()
    {
        return view('livewire.datasektoral.show')->layout('components.layouts.public');
    }
}