<div>
  <p class="mt-6 text-xl">Categories</p>

  <form wire:submit={{ $category ? 'updateCategory' : 'createCategory' }} class="flex items-end gap-2">
    <label class="form-control w-full max-w-xs">
      <div class="label">
        <span class="label-text">Form Category</span>
      </div>
      <input type="text" wire:model='name' name="name" id="formCategoryName" placeholder="Category name"
        class="input input-sm input-bordered w-full max-w-xs">
      @error('name')
        <div class="label">
          <span class="label-text-alt">
            {{ $message }}
          </span>
        </div>
      @enderror
    </label>
    <button type="submit" class="btn btn-sm">Save</button>
    @if ($category)
      <button type="button" wire:click='clearForm' class="btn btn-outline btn-error btn-sm">Cancel</button>
    @endif
  </form>

  <table class="mt-6">
    <thead>
      <tr>
        <th>No.</th>
        <th>Name</th>
        <th class="w-0">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($this->categories as $category)
        <tr>
          <td>
            {{ ($this->categories->currentPage() - 1) * $this->categories->perPage() + $loop->iteration }}
          </td>
          <td>{{ $category->name }}</td>
          <td>
            <div class="flex items-center gap-2">
              <button wire:click='selectCategory({{ $category->id }})' class="btn btn-outline btn-warning btn-xs">
                Edit
              </button>
              <button wire:click='deleteCategory({{ $category->id }})'
                wire:confirm="Are you sure you want to delete this category?" class="btn btn-outline btn-error btn-xs">
                Delete
              </button>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $this->categories->links() }}
</div>
