<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Details - {{ $product->product_name }}</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Optional: Bootstrap Icons (if needed) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    .image-preview {
      max-width: 120px;
      margin: 10px;
      border: 1px solid #ddd;
      padding: 5px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }
    .image-preview:hover {
      transform: scale(1.05);
    }
    .primary-image {
      max-width: 16%;
      height: auto;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <h2>Product Details</h2>
  <div class="card">
    <div class="card-header">
      <h3>{{ $product->product_name }}</h3>
    </div>
    <div class="card-body">
      <p><strong>Description:</strong> {{ $product->description }}</p>
      <p><strong>Price:</strong> {{ number_format($product->price, 2) }}</p>
      <p>
        <strong>Category:</strong> 
        {{ $product->category ? $product->category->category_name : 'N/A' }}
      </p>
      <div class="mb-3">
        <h4>Primary Image</h4>
        @if($product->product_image)
          <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="primary-image">
        @endif
      </div>
      <div class="mb-3">
        <h4>Additional Images</h4>
        <div class="d-flex flex-wrap">
          @if($product->images && $product->images->count())
            @foreach($product->images as $img)
              <img src="{{ asset('storage/' . $img->image) }}" alt="Additional Image" class="image-preview">
            @endforeach
          @else
            <p>No additional images available.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('product.view') }}" class="btn btn-secondary mt-3">Back to Products</a>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
