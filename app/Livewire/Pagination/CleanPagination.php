<?php
//
//namespace App\Livewire\Pagination;
//namespace App\Http\Livewire\Pagination;
//
//use Livewire\Component;
//
//class CleanPagination extends Component
//{
//    public $items;
//
//    public function mount($items)
//    {
//        $this->items = $items;
//    }
//
//    public function render()
//    {
//        return view('livewire.pagination.clean-pagination', [
//            'items' => $this->items, // Use the already paginated items
//        ]);
//    }
//}
//
////use Livewire\Component;
////use Livewire\WithPagination;
////
////class CleanPagination extends Component
////{
////    use WithPagination;
////
////    public $items;
////
////    public function mount($items)
////    {
////        $this->items = $items;
////    }
////
////    public function render()
////    {
////        $paginatedItems = $this->items->paginate(9); // Paginate items, 9 per page
////
////        return view('livewire.pagination.clean-pagination', [
////            'items' => $paginatedItems
////        ]);
////    }
////}
//
