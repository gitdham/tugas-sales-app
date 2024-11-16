<div x-data="{ formSaleIsOpen: false }" x-cloak class="relative">
  <div class="mt-6 flex w-full items-center justify-between">
    <p class="text-xl">Sales</p>
    <button type="button" @click="formSaleIsOpen=true" class="btn btn-sm">
      Add Sales
    </button>
  </div>

  <label class="form-control mt-4 w-full">
    <input type="text" wire:model.live='search' name="name" placeholder="Search"
      class="input input-sm input-bordered w-full">
  </label>

  <div class="overflow-x-scroll">
    <table class="mt-6">
      <thead>
        <tr>
          <th>No.</th>
          <th wire:click="setOrder('date')" class="cursor-pointer">Date</th>
          <th wire:click="setOrder('price')" class="cursor-pointer">Total Amount</th>
          <th class="w-0">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($this->sales as $sale)
          <tr>
            <td>
              {{ ($this->sales->currentPage() - 1) * $this->sales->perPage() + $loop->iteration }}
            </td>
            <td>{{ $sale->date }}</td>
            <td x-text="idrFormat({{ $sale->total_amount }})" x-cloak></td>
            <td>
              <div class="flex items-center gap-2">
                <button wire:click='selectSale({{ $sale->id }})' class="btn btn-outline btn-warning btn-xs">
                  Edit
                </button>
                <button wire:click='deleteSale({{ $sale->id }})'
                  wire:confirm="Are you sure you want to delete this product?" class="btn btn-outline btn-error btn-xs">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $this->sales->links() }}

  <div x-on:close-product-form.window="formSaleIsOpen=false" x-on:open-product-form.window="formSaleIsOpen=true"
    class="fixed right-0 top-0 flex h-[100vh] w-full max-w-sm flex-col gap-4 overflow-scroll bg-base-100 p-4 shadow-lg duration-300"
    :class="{ 'translate-x-full': !formSaleIsOpen }">
    <div class="flex items-center justify-between">
      <p class="text-xl">Form Sales</p>
      <button type="button" @click="formSaleIsOpen=false" class="btn btn-outline btn-error btn-sm self-end">
        Close
      </button>
    </div>

    <form wire:submit={{ $sale ? 'updateProduct' : 'createProduct' }} class="flex flex-col gap-2">
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Date</span>
        </div>
        <input type="date" wire:model='date' name="date" class="input input-sm input-bordered w-full">
        @error('date')
          <div class="label">
            <span class="label-text-alt text-rose-500">
              {{ $message }}
            </span>
          </div>
        @enderror
      </label>

      <div class="flex gap-1">
        <label class="w-fulls form-control w-full">
          <div class="label">
            <span class="label-text">Pick product</span>
          </div>
          <select wire:model='product' class="select select-bordered select-sm">
            <option value="" disabled selected>Pick product</option>
            @foreach ($this->products as $product)
              <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
          </select>
          @error('product')
            <div class="label">
              <span class="label-text-alt text-rose-500">
                {{ $message }}
              </span>
            </div>
          @enderror
        </label>

        <label class="form-control w-fit">
          <div class="label">
            <span class="label-text">Qty</span>
          </div>
          <input type="text" wire:model='qty' name="qty" class="input input-sm input-bordered w-full">
          @error('qty')
            <div class="label">
              <span class="label-text-alt text-rose-500">
                {{ $message }}
              </span>
            </div>
          @enderror
        </label>

        <button type="button" class="btn btn-sm mt-2 w-fit self-end">Add</button>
      </div>

      <table class="mt-4">
        <thead>
          <th>No</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Qty</th>
        </thead>
      </table>

      <button type="submit" class="btn btn-sm mt-2 w-full self-end">Save Sales</button>
    </form>
  </div>
</div>
