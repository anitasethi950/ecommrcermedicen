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


    <style>
        /* Custom CSS for the dropdown */
        #productCategory {
            color: white;
            background-color: #343a40;
        }

        #productCategory option {
            color: white;
            background-color: #343a40;
        }

        #productCategory:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
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
                                    <th scope="col">Price</th>
                                    <th scope="col">Product Image</th>
                                    <th scope="col">Actions</th>
                                    <th scope="col">Product Detalis</th>
                                    <th scope="col">Published</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>

                                        <td>{{ $product->id }}</td>

                                        <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td>


                                        <td>{{ $product->product_name }}</td>

                                        <td>₹{{ number_format($product->price, 2) }}</td>


                                        <td>
                                            @if ($product->product_image)
                                                <img src="{{ asset('storage/' . $product->product_image) }}"
                                                    alt="{{ $product->product_name }}" style="max-width: 100px;">
                                            @endif
                                        </td>

                                        <td>

                                            <a href="javascript:void(0)" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal" data-bs-target="#editProductModal" type="button"
                                                id="editButton" data-id="{{ $product->id }}"
                                                data-name="{{ $product->product_name }}"
                                                data-category="{{ $product->category_id }}"
                                                data-price="{{ $product->price }}"
                                                data-description="{{ $product->description }}"
                                                data-image="{{ $product->product_image }}">Edit</a>



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
            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog"
                aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="productName">Product Name</label>
                                    <input type="text" id="productName" name="product_name" class="form-control"
                                        required>
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label for="productCategory">Category</label>
                                    <select id="productCategory" name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if ($category->id == $product->category_id) selected @endif>
                                                {{ $category->category_name	 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Price -->
                                <div class="form-group">
                                    <label for="productPrice">Price</label>
                                    <input type="number" id="productPrice" name="price" class="form-control" required>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="productDescription">Description</label>
                                    <textarea id="productDescription" name="description" class="form-control" required></textarea>
                                </div>

                                <!-- Product Image -->
                                <div class="form-group">
                                    <label for="productImage">Product Image</label>
                                    <input type="file" id="productImage" name="product_image" class="form-control">
                                    <!-- Display current image if exists -->
                                    <img id="currentImage" src="" alt="Current Image"
                                        style="max-width: 100px; margin-top: 10px;">
                                </div>

                                <!-- Additional Images -->
                                <div class="form-group">
                                    <label for="images" class="form-label">Additional Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple>

                                    <!-- Display existing additional images if any -->
                                    <div id="existingImages" class="mt-2 d-flex flex-wrap">
                                        @if ($product->images && $product->images->count())
                                            @foreach ($product->images as $img)
                                                <div class="position-relative me-2 mb-2" id="image_{{ $img->id }}">
                                                    <img src="{{ asset('storage/' . $img->image) }}"
                                                        alt="Additional Image" style="max-width: 150px;">
                                                    <!-- Delete form overlay -->
                                                    <form action="{{ route('product_images.destroy', $img->id) }}"
                                                        method="POST" class="position-absolute top-0 end-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this image?');"
                                                            style="border-radius:50%; padding:2px 6px;">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No additional images added yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
        $('#editProductModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var productId = button.data('id'); // Extract product ID from data-id attribute
            var productName = button.data('name'); // Extract product name from data-name
            var productCategory = button.data('category'); // Extract category from data-category
            var productPrice = button.data('price'); // Extract price from data-price
            var productDescription = button.data('description'); // Extract description
            var productImage = button.data('image'); // Extract product image URL from data-image

            // Populate modal fields with the current product data
            $('#productName').val(productName);
            $('#productCategory').val(productCategory);
            $('#productPrice').val(productPrice);
            $('#productDescription').val(productDescription);

            // Set the current product image in the modal
            if (productImage) {
                $('#currentImage').attr('src', '/storage/' + productImage);
            } else {
                $('#currentImage').attr('src', ''); // Reset if no image
            }

            // Update the form action URL with the correct product ID for the update
            $('#editProductForm').attr('action', '/products/' + productId);

            // Populate existing images dynamically (if any)
            // We need to ensure these images are displayed correctly when the modal opens
            var existingImages = button.data('images'); // Array of image URLs
            var imagesContainer = $('#existingImages');
            imagesContainer.empty(); // Clear any previous images

            if (existingImages && existingImages.length > 0) {
                existingImages.forEach(function(imageUrl) {
                    imagesContainer.append(`
                    <div class="position-relative me-2 mb-2">
                        <img src="/storage/${imageUrl}" alt="Additional Image" style="max-width: 150px;">
                        <form action="/product_images/${imageUrl.id}" method="POST" class="position-absolute top-0 end-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this image?');"
                                    style="border-radius:50%; padding:2px 6px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                    </div>
                `);
                });
            } else {
                imagesContainer.append('<p>No additional images added yet.</p>');
            }
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
