<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Baby GIF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-5">

    <h2 class="mb-4">Upload Baby GIF</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('upload.baby') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="baby_img" class="form-label">Baby GIF</label>
            <input type="file" name="baby_img" id="baby_img" accept="image/gif" class="form-control" required>
            @error('baby_img') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="baby_name" class="form-label">Baby Name</label>
            <input type="text" name="baby_name" id="baby_name" class="form-control" required>
            @error('baby_name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

</body>

</html>
