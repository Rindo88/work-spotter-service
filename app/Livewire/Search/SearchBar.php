<?php

namespace App\Livewire\Search;

use App\Models\Service;
use App\Models\Vendor;
use Livewire\Component;

class SearchBar extends Component
{
    public $query = '';
    public $vendors = [];
    public $services = [];
    public $showSuggestions = false;

    public function updatedQuery()
    {
        $this->showSuggestions = true;
        
        if (strlen($this->query) >= 2) {
            $this->vendors = Vendor::where('business_name', 'like', '%' . $this->query . '%')
                ->orWhere('description', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();

            $this->services = Service::where('name', 'like', '%' . $this->query . '%')
                ->orWhere('description', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();
        } else {
            $this->resetSuggestions();
        }
    }

    public function search()
    {
        if (strlen($this->query) >= 2) {
            return redirect()->route('search.results', ['query' => $this->query]);
        }
    }

    public function resetSuggestions()
    {
        $this->vendors = [];
        $this->services = [];
        $this->showSuggestions = false;
    }

    public function render()
    {
        return view('livewire.search.search-bar');
    }
}