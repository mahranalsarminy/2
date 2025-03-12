@extends('layouts.admin')
@section('title', admin_lang('Wallpaper Categories'))
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ admin_lang('Wallpaper Categories') }}</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fa fa-plus me-2"></i>{{ admin_lang('Add New') }}
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>{{ admin_lang('ID') }}</th>
                            <th>{{ admin_lang('Image') }}</th>
                            <th>{{ admin_lang('Name') }}</th>
                            <th>{{ admin_lang('Slug') }}</th>
                            <th>{{ admin_lang('Wallpapers') }}</th>
                            <th>{{ admin_lang('Status') }}</th>
                            <th>{{ admin_lang('Created Date') }}</th>
                            <th>{{ admin_lang('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    @if($category->thumbnail)
                                        <img src="{{ $category->thumbnail }}" alt="{{ $category->name }}" width="50" height="50" class="rounded">
                                    @else
                                        <div class="bg-light rounded" style="width: 50px; height: 50px;"></div>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->wallpapers->count() }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ admin_lang('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary edit-btn"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-slug="{{ $category->slug }}"
                                            data-thumbnail="{{ $category->thumbnail }}"
                                            data-description="{{ $category->description }}"
                                            data-is_active="{{ $category->is_active }}"
                                            data-display_order="{{ $category->display_order }}"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                title="{{ admin_lang('Delete') }}">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">{{ admin_lang('Add Category') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Slug') }}</label>
                            <input type="text" name="slug" class="form-control">
                            <small class="text-muted">{{ admin_lang('Leave empty to generate automatically') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Thumbnail') }}</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Description') }}</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Display Order') }}</label>
                            <input type="number" name="display_order" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" checked>
                                <label class="form-check-label">{{ admin_lang('Active') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ admin_lang('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ admin_lang('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ admin_lang('Edit Category') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Slug') }}</label>
                            <input type="text" name="slug" id="edit-slug" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Current Thumbnail') }}</label>
                            <div id="current-thumbnail-container" class="mb-2">
                                <img id="current-thumbnail" src="" alt="" class="img-thumbnail" style="max-width: 150px; display: none;">
                                <div id="no-thumbnail" class="bg-light rounded p-3 text-center" style="display: none;">
                                    {{ admin_lang('No thumbnail') }}
                                </div>
                            </div>
                            <label class="form-label">{{ admin_lang('New Thumbnail') }}</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Description') }}</label>
                            <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Display Order') }}</label>
                            <input type="number" name="display_order" id="edit-display-order" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit-is-active">
                                <label class="form-check-label">{{ admin_lang('Active') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ admin_lang('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ admin_lang('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Confirm delete
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                
                if (confirm("{{ admin_lang('Are you sure you want to delete this category?') }}")) {
                    this.submit();
                }
            });
            
            // Edit modal data
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const slug = $(this).data('slug');
                const thumbnail = $(this).data('thumbnail');
                const description = $(this).data('description');
                const isActive = $(this).data('is_active');
                const displayOrder = $(this).data('display_order');
                
                // Set form action
                $('#editForm').attr('action', `{{ url('admin/categories') }}/${id}`);
                
                // Set form values
                $('#edit-name').val(name);
                $('#edit-slug').val(slug);
                $('#edit-description').val(description);
                $('#edit-display-order').val(displayOrder);
                $('#edit-is-active').prop('checked', isActive == 1);
                
                // Set thumbnail preview
                if (thumbnail) {
                    $('#current-thumbnail').attr('src', thumbnail).show();
                    $('#no-thumbnail').hide();
                } else {
                    $('#current-thumbnail').hide();
                    $('#no-thumbnail').show();
                }
            });
        });
    </script>
    @endpush
@endsection