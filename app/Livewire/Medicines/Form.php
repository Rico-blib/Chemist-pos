<?php

namespace App\Livewire\Medicines;

use Livewire\Component;
use App\Models\Medicine;

class Form extends Component
{
    public $medicineId = null;
    public $name, $category, $manufacturer, $quantity, $price, $expiry_date;
    public bool $isEditing = false;

    protected $rules = [
        'name' => 'required|string',
        'category' => 'nullable|string',
        'manufacturer' => 'nullable|string',
        'quantity' => 'required|integer',
        'price' => 'required|numeric',
        'expiry_date' => 'required|date',
    ];

    public function mount($medicine = null)
    {
        if ($medicine) {
            $this->fillFromModel($medicine);
        }
    }

    public function fillFromModel(Medicine $medicine)
    {
        $this->medicineId = $medicine->id;
        $this->name = $medicine->name;
        $this->category = $medicine->category;
        $this->manufacturer = $medicine->manufacturer;
        $this->quantity = $medicine->quantity;
        $this->price = $medicine->price;
        $this->expiry_date = $medicine->expiry_date;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = $this->only([
            'name',
            'category',
            'manufacturer',
            'quantity',
            'price',
            'expiry_date'
        ]);

        if ($this->medicineId) {
            Medicine::findOrFail($this->medicineId)->update($data);
            session()->flash('success', 'Medicine updated successfully!');
        } else {
            Medicine::create($data);
            session()->flash('success', 'Medicine added successfully!');
        }

        $this->resetForm();

        $this->dispatch('formSubmitted'); // Livewire listener
       
    }

    public function resetForm()
    {
        $this->medicineId = null;
        $this->name = null;
        $this->category = null;
        $this->manufacturer = null;
        $this->quantity = null;
        $this->price = null;
        $this->expiry_date = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.medicines.form');
    }
}
