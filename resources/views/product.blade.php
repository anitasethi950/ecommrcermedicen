<!DOCTYPE html>
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
                                            <button type="button" id="cropBtn" class="btn btn-primary">Crop</button>
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
</body>

</html>
