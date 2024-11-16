<div x-data="{ formProductIsOpen: false }" x-cloak class="relative">
  <div class="mt-6 flex w-full items-center justify-between">
    <p class="text-xl">Products</p>
    <button type="button" @click="formProductIsOpen=true" class="btn btn-sm">Add Product</button>
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
          <th wire:click="setOrder('name')" class="cursor-pointer">Name</th>
          <th wire:click="setOrder('price')" class="cursor-pointer">Price</th>
          <th wire:click="setOrder('stock')" class="cursor-pointer">Stock</th>
          <th wire:click="setOrder('category')" class="cursor-pointer">Category</th>
          <th class="w-0">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($this->products as $product)
          <tr>
            <td>
              {{ ($this->products->currentPage() - 1) * $this->products->perPage() + $loop->iteration }}
            </td>
            <td>{{ $product->name }}</td>
            <td x-text="idrFormat({{ $product->price }})" x-cloak></td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->category->name }}</td>
            <td>
              <div class="flex items-center gap-2">
                <button wire:click='selectProduct({{ $product->id }})' class="btn btn-outline btn-warning btn-xs">
                  Edit
                </button>
                <button wire:click='deleteProduct({{ $product->id }})'
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
  {{ $this->products->links() }}

  <div x-on:close-product-form.window="formProductIsOpen=false" x-on:open-product-form.window="formProductIsOpen=true"
    class="fixed right-0 top-0 flex h-[100vh] w-full max-w-sm flex-col gap-4 overflow-scroll bg-base-100 p-4 shadow-lg duration-300"
    :class="{ 'translate-x-full': !formProductIsOpen }">
    <div class="flex items-center justify-between">
      <p class="text-xl">Form Product</p>
      <button type="button" @click="formProductIsOpen=false;$wire.resetForm()"
        class="btn btn-outline btn-error btn-sm self-end">
        Close
      </button>
    </div>

    <form wire:submit={{ $product ? 'updateProduct' : 'createProduct' }} class="flex flex-col gap-2">
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Product Name</span>
        </div>
        <input type="text" wire:model='name' name="name" placeholder="Product name"
          class="input input-sm input-bordered w-full">
        @error('name')
          <div class="label">
            <span class="label-text-alt text-rose-500">
              {{ $message }}
            </span>
          </div>
        @enderror
      </label>

      <label class="w-fulls form-control">
        <div class="label">
          <span class="label-text">Pick category</span>
        </div>
        <select wire:model='category' class="select select-bordered select-sm">
          <option value="" disabled selected>Pick category</option>
          @foreach ($this->categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
        @error('category')
          <div class="label">
            <span class="label-text-alt text-rose-500">
              {{ $message }}
            </span>
          </div>
        @enderror
      </label>

      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Price</span>
        </div>
        <input type="text" wire:model='price' name="price" placeholder="Price"
          class="input input-sm input-bordered w-full">
        @error('price')
          <div class="label">
            <span class="label-text-alt text-rose-500">
              {{ $message }}
            </span>
          </div>
        @enderror
      </label>

      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Stock</span>
        </div>
        <input type="text" wire:model='stock' name="stock" placeholder="stock"
          class="input input-sm input-bordered w-full">
        @error('stock')
          <div class="label">
            <span class="label-text-alt text-rose-500">
              {{ $message }}
            </span>
          </div>
        @enderror
      </label>

      <button type="submit" class="btn btn-sm mt-2 w-full self-end">Save</button>
    </form>
  </div>
</div>
