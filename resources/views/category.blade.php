<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Category</title>
  <!-- Bootstrap CSS (using CDN for quick styling) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Optional: Include your app.css if you have additional styling -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
    }
    .card {
      margin-top: 50px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background-color: #007bff;
      color: white;
      text-align: center;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <!-- Adjust column width as needed -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h2>Add New Category</h2>
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

            <form action="{{ route('category.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Save Category</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
