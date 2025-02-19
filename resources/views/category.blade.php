@extends('layout.app')

@section('content')
    <style>
        @media (max-width: 767px) {
            #addCategoryForm {
                order: 1;
                /* Show the Add Category form first on mobile */
            }

            #viewCategoryTable {
                order: 2;
                /* View Category table will be second on mobile */
            }

            /* Make both form and table take full width on mobile */
            .col-12 {
                width: 100% !important;
            }

            /* Optionally, you can add some margin between elements on mobile */
            .row.g-4 {
                margin-bottom: 15px;
            }
        }
    </style>


    <style>
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #14161c !important;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: .3rem;
            outline: 0;
        }


        .btn-primary {
            color: #ffffff;
            background-color: #000000;
            border-color: #33363c;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #000000;
            border-color: #000000;
        }
    </style>



    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <!-- View Category - 70% width -->
            <div class="col-12 col-md-8" id="viewCategoryTable">
                <div class="bg-secondary rounded h-100 p-4">
                    <h6 class="mb-4">View Category</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $categorys)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $categorys->category_name }}</td>

                                        <td>
                                            <!-- Edit Button -->
                                            <button href="javascript:void(0)" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal" data-bs-target="#editModal" type="button"
                                                id="editButton" data-id="{{ $categorys->id }}"
                                                data-name="{{ $categorys->category_name }}">
                                                Edit
                                            </button>



                                            <!-- Delete Form -->
                                            <form action="{{ route('category.destroy', $categorys->id) }}" method="POST"
                                                style="display:inline-block;" id="deleteForm{{ $categorys->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ $categorys->id }}')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- editmodalcategory --}}
            <!-- Edit Category Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editCategoryForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Category Name:</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Add Category Form - 30% width -->
            <div class="col-12 col-md-4">
                <div class="bg-secondary rounded h-100 p-4" id="addCategoryForm">
                    <h6 class="mb-4">Add Category</h6>
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name:</label>
                            <input type="text" class="form-control" id="category_name" name="category_name"
                                aria-describedby="emailHelp" placeholder="Enter Category Name" required>
                        </div>

                        <!-- Align Save button to the right -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



<script>
    $(document).ready(function() {
        // Handle the 'click' event for any Edit button
        $('button[id^="editButton"]').on('click', function(event) {
            // Get the button that was clicked
            var button = $(this);

            // Extract the category ID and name from the data attributes
            var categoryId = button.data('id');
            var categoryName = button.data('name');

            // Log the values to verify they're correct
            console.log('Category ID:', categoryId);
            console.log('Category Name:', categoryName);

            // Show the modal
            $('#editModal').modal('show'); // Manually trigger the modal

            // Update the modal's form fields with the extracted data
            var modal = $('#editModal');
            modal.find('#category_name').val(categoryName); // Set the category name in the input field
            modal.find('#editCategoryForm').attr('action', '/category/' +
            categoryId); // Update form action URL dynamically
        });

        // Optional: Handle modal close to clear data (if required)
        $('#editModal').on('hidden.bs.modal', function() {
            var modal = $(this);
            modal.find('#category_name').val(''); // Reset the input field
            modal.find('#editCategoryForm').attr('action', ''); // Reset form action
        });
    });
</script>



<script>
    // Function to confirm delete using SweetAlert
    function confirmDelete(categoryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the delete form if confirmed
                document.getElementById('deleteForm' + categoryId).submit();
            }
        });
    }
</script>
