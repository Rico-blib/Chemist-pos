<?php

namespace App\Livewire\Medicines;

use Livewire\Component;
use App\Models\Medicine;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $showModal = false;
    public $editingMedicine = null;

    protected $listeners = [
        'formSubmitted' => 'onFormSubmitted',
    ];

    public function create()
    {
        $this->editingMedicine = null;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->editingMedicine = Medicine::findOrFail($id);
        $this->showModal = true;
    }

    public function delete($id)
    {
        Medicine::findOrFail($id)->delete();
        session()->flash('success', 'Medicine deleted successfully!');
    }

    public function onFormSubmitted()
    {
        $this->showModal = false;
        $this->editingMedicine = null;
        $this->resetPage(); // refresh paginated list
    }

    public function render()
    {
        $query = Medicine::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('manufacturer', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $medicines = $query->latest()->paginate(10);
        $categories = Medicine::distinct()->pluck('category')->filter()->values();

        return view('livewire.medicines.index', [
            'medicines' => $medicines,
            'categories' => $categories,
        ]);
    }
}
