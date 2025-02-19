
@extends('layout.app')

@section('content')
   
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
      <div class="col-sm-12 col-xl-6">
          <div class="bg-secondary rounded h-100 p-4">
              <h6 class="mb-4">Edit Category</h6>
              <form action="{{ route('category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                  
                  <div class="mb-3">
                      <label for="text" class="form-label">Category Name:</label>
                      <input type="text" class="form-control"   name="category_name" id="category_name" 
                          aria-describedby="emailHelp" placeholder="Enter Category Name" required>
                     
                  </div>
                  
                  <button type="submit" class="btn btn-primary"> Update</button>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection

@section('scripts')
    <!-- Add any page-specific scripts here -->
@endsection






























<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Category</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2>Edit Category</h2>
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <form action="{{ route('category.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="category_name" class="form-label">Category Name</label>
      <input type="text" name="category_name" id="category_name" class="form-control" value="{{ $category->category_name }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Category</button>
  </form>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
