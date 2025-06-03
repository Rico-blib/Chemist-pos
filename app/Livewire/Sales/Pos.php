<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class Pos extends Component
{
    public $search = '';
    public $cart = [];
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;

    public function addToCart($medicineId)
    {
        $medicine = Medicine::find($medicineId);

        if (!$medicine) {
            session()->flash('error', 'Medicine not found.');
            return;
        }

        // Add or update cart item
        if (isset($this->cart[$medicineId])) {
            $this->cart[$medicineId]['quantity'] += 1;
        } else {
            $this->cart[$medicineId] = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'price' => (float) $medicine->price,
                'quantity' => 1,
            ];
        }

        $this->calculateTotals();

        // Optional: clear search after adding
        $this->reset('search');
    }

    public function removeFromCart($medicineId)
    {
        unset($this->cart[$medicineId]);
        $this->calculateTotals();
    }

    public function incrementQuantity($medicineId)
    {
        if (isset($this->cart[$medicineId])) {
            $this->cart[$medicineId]['quantity']++;
            $this->calculateTotals();
        }
    }

    public function decrementQuantity($medicineId)
    {
        if (isset($this->cart[$medicineId])) {
            $this->cart[$medicineId]['quantity'] = max(1, $this->cart[$medicineId]['quantity'] - 1);
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;

        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }

        $this->tax = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->tax;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'total' => $this->total,
            ]);

            foreach ($this->cart as $item) {
                $medicine = Medicine::findOrFail($item['id']);

                if ($medicine->quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$medicine->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $medicine->quantity -= $item['quantity'];
                $medicine->save();
            }

            DB::commit();

            $this->reset(['cart', 'subtotal', 'tax', 'total']);
            session()->flash('success', 'Sale completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $medicines = [];

        if (strlen(trim($this->search)) > 1) {
            $medicines = Medicine::where('name', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get()
                ->toArray();
        }

        return view('livewire.sales.pos', [
            'medicines' => $medicines,
        ]);
    }
}
