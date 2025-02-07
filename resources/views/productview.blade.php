<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product List</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Product List</h2>
    <!-- Link to add a new product -->
    <a href="{{ route('admin.product') }}" class="btn btn-success mb-3">Add New Product</a>

    <!-- Display a success message if available -->
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Product Table -->
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Category</th>
          <th>Product Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Feature Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr>
            
            <td>{{ $product->id }}</td>
           
            <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td>

            <!-- Product Name -->
            <td>{{ $product->product_name }}</td>
            <!-- Description -->
            <td>{{ $product->description }}</td>
            <!-- Price (formatted to 2 decimal places) -->
            <td>${{ number_format($product->price, 2) }}</td>
            <!-- Feature Image -->
            <td>
                @if($product->product_image)
                  <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" style="max-width: 100px;">
                @endif
              </td>
            <!-- Action Buttons -->
            <td>
              <!-- Edit Button -->
              <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <!-- Delete Form -->
              <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
