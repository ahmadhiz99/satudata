<?php

namespace App\Livewire\Management\Datasets;

use App\Models\DatasetUpload;
use Livewire\Component;

class Show extends Component
{
    public DatasetUpload $upload;

    public function mount(DatasetUpload $upload)
    {
        $this->upload = $upload;
    }

    public function render()
    {
        return view('livewire.management.datasets.show');
    }
}