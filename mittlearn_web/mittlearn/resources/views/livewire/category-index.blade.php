<div x-data="{ open: @entangle('showModal').defer }">
    <!-- Trigger button to open the modal -->
    <button type="button" class="btn btn-sm btn-info  me-2" @click="open = true" wire:click="add('{{ $category }}')"
        title="Add Sub-Category">
        <i class="bi bi-plus-lg"></i>
    </button>
    <div x-show="open" @click.away="open = false" @keydown.escape.window="open = false" style="display: none;"
        x-transition>
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subcategory</h5>
                        <button type="button" class="btn-close" @click="open = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateCategory" class="row g-3">
                            <label for="name" class="col-sm-2 col-form-label ">Category</label>
                            <div class="col-sm-10">
                                <input type="text" id="name" class="form-control" wire:model="name" disabled>
                            </div>
                            <label for="category_name" class="col-sm-2 col-form-label required">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" id="category_name" class="form-control"
                                    wire:model="category_name">
                            </div>
                            <label for="description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <input id="description" class="form-control" wire:model="description"></input>
                            </div>
                            <label for="icon" class="col-sm-2 col-form-label">Icon</label>
                            <div class="col-sm-10">
                                <input type="file" id="icon" class="form-control" wire:model="icon" />
                            </div>
                            <label for="status"class="col-sm-2 col-form-label required">Status</label>
                            <div class="col-sm-10">
                                <select id="status" class="form-control" wire:model="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
