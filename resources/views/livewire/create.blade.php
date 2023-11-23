<form>
    <div class="form-group mb-3">
        <label for="categoryName">Name:</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="categoryName" placeholder="Enter Name" wire:model="name">
        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div class="form-group mb-3">
        <label for="categoryDescription">Description:</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="categoryDescription" wire:model="description" placeholder="Enter Description"></textarea>
        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    @if ($image)
        Photo Preview:
        <img style="width: 50px" src="{{ $image->temporaryUrl() }}">
    @endif
    <div class="form-group mb-3">
        <label for="image">File:</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" wire:model="image">
        @error('image') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div wire:loading wire:target="image" class="fade-in">Uploading...</div>
    <div class="d-grid gap-2">
        <button wire:click.prevent="add()" class="btn btn-success btn-block">Save</button>
    </div>
</form>
<style>
    .fade-in {
        opacity: 0;
        animation: fadeIn 0.5s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
