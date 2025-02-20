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
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <!-- View Category - 70% width -->
            <div class="col-12 col-md-8" id="viewCategoryTable">
                <div class="bg-secondary rounded h-100 p-4">
                    <h6 class="mb-4">View Product</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Product Image</th>
                                    <th scope="col">Actions</th>
                                    <th scope="col">Publish</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>

                                        <td>{{ $product->id }}</td>

                                        <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td>

                                        <!-- Product Name -->
                                        <td>{{ $product->product_name }}</td>
                                        <!-- Description -->
                                        <td>{{ $product->description }}</td>
                                        <!-- Price (formatted to 2 decimal places) -->
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <!-- Feature Image -->
                                        <td>
                                            @if ($product->product_image)
                                                <img src="{{ asset('storage/' . $product->product_image) }}"
                                                    alt="{{ $product->product_name }}" style="max-width: 100px;">
                                            @endif
                                        </td>
                                        <!-- Action Buttons -->
                                        <td>
                                            <!-- Edit Button -->
                                            <!-- Edit Button with Icon -->
                                            <a href="javascript:void(0);" data-toggle="modal"
                                                data-target="#editProductModal" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>


                                            <!-- Delete Form -->
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                            </form>
                                        </td>
                                        <td>

                                            <a href="{{ route('productimage.view', $product->id) }}"
                                                class="btn btn-sm btn-warning">View More Image</a>

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <!-- Modal -->
            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog"
                aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editProductForm" method="POST"
                            action="{{ route('products.update', ['product' => ':id']) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <!-- Input fields for product details -->
                                <div class="form-group">
                                    <label for="productName">Product Name</label>
                                    <input type="text" id="productName" name="product_name" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="productCategory">Category</label>
                                    <input type="text" id="productCategory" name="category_id" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="productPrice">Price</label>
                                    <input type="number" id="productPrice" name="price" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="productDescription">Description</label>
                                    <textarea id="productDescription" name="description" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Add Category Form - 30% width -->
            <div class="col-12 col-md-4">
                <div class="bg-secondary rounded h-100 p-4" id="addCategoryForm">
                    <h6 class="mb-4">Add Product</h6>
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Product Name:</label>
                            <input type="text" class="form-control" id="name" name="product_name"
                                aria-describedby="emailHelp" placeholder="Enter Product Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_name" class="form-label">Description:</label>
                            <input type="text" class="form-control" name="description" id="description"
                                aria-describedby="emailHelp" placeholder="Enter Description" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Price:</label>
                            <input type="text" class="form-control" id="price" name="price"
                                aria-describedby="emailHelp" placeholder="Price" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label">Product Image</label>
                            <input class="form-control bg-dark" type="file"id="product_image" name="product_image"
                                multiple>

                        </div>
                        <input type="hidden" name="cropped_image" id="cropped_image">

                        <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img id="cropImage" src="" alt="Image for cropping">
                                    </div>



                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" id="cropBtn" class="btn btn-primary">Crop</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label">Additional Image</label>
                            <input class="form-control bg-dark" type="file" id="images" name="images[]" multiple>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Cropper.js JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
    let cropper;
    const productImageInput = document.getElementById('product_image');
    const cropImage = document.getElementById('cropImage');
    const cropBtn = document.getElementById('cropBtn');
    const croppedImageInput = document.getElementById('cropped_image');
    const cropModalEl = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalEl);

    // When a file is selected, load it into the modal for cropping.
    productImageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                // Show the modal.
                cropModal.show();

                // Initialize Cropper.js (destroy previous instance if exists)
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropImage, {
                    aspectRatio: 16 / 9, // Adjust aspect ratio as needed
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // When the crop button is clicked, get the cropped image data.
    cropBtn.addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 800, // Set desired cropped image width
                height: 450, // Set desired cropped image height
            });
            // Convert the canvas to a base64 encoded image (JPEG format).
            const base64Image = canvas.toDataURL('image/jpeg');
            // Set the hidden input's value to the base64 string.
            croppedImageInput.value = base64Image;
            // Optionally, you can update the preview or reset the file input.
            // Hide the modal.
            cropModal.hide();
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Trigger modal open when the "Edit" button is clicked
        $('a[data-toggle="modal"]').click(function() {
            // Get the product details from the clicked element (you can customize this)
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var productCategory = $(this).data('category');
            var productPrice = $(this).data('price');
            var productDescription = $(this).data('description');

            // Populate modal fields with the current product data
            $('#productName').val(productName);
            $('#productCategory').val(productCategory);
            $('#productPrice').val(productPrice);
            $('#productDescription').val(productDescription);

            // Update the form action URL with the correct product ID for the update
            $('#editProductForm').attr('action', '/products/' + productId);
        });
    });
</script>


























{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <!-- Bootstrap 5 CSS (using CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 50px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Add New Product</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Category Dropdown -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Product Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="product_name"
                                    placeholder="Enter Product Name" required>
                            </div>
                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Product Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control"
                                    placeholder="Enter product description"></textarea>
                            </div>
                            <!-- Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price"
                                    placeholder="Enter product price" required>
                            </div>
                            <!-- Stock -->

                            <!-- Feature Image -->
                            <div class="mb-3">
                                <label for="feature_image" class="form-label">Feature Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image"
                                    required>
                            </div>
                            <input type="hidden" name="cropped_image" id="cropped_image">

                            <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img id="cropImage" src="" alt="Image for cropping">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" id="cropBtn"
                                                class="btn btn-primary">Crop</button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="mb-3">
                                <label for="images" class="form-label">Additional Images (optional)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cropper.js JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  
</body>

</html> --}}
