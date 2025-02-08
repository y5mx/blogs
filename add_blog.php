<?php
session_start();
require_once 'config.php';

// Handle file upload
function handleFileUpload($file, $type)
{
    // Determine the target directory based on the type
    $target_dir = $type === 'banner' ? '../blogs/uploads/banners/' : '../blogs/uploads/attachments/';
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];

    // Check if the file type is allowed
    if (!in_array($file_extension, $allowed_extensions)) {
        return ['error' => 'Invalid file type'];
    }

    // Generate a unique filename
    $new_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    }

    // Return an error if the file upload failed
    return ['error' => 'Failed to upload file'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the English slug directly from the form input
    $slug = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING);

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $article = $_POST['article']; // Will be sanitized before display
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

    // Handle banner upload
    $banner_path = '';
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $banner_result = handleFileUpload($_FILES['banner'], 'banner');
        if (isset($banner_result['filename'])) {
            $banner_path = $banner_result['filename'];
        }
    }

    // Handle attachments
    $attachments = [];
    if (isset($_FILES['attachments'])) {
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                $attachment_file = [
                    'name' => $_FILES['attachments']['name'][$key],
                    'tmp_name' => $tmp_name,
                    'error' => $_FILES['attachments']['error'][$key],
                    'type' => $_FILES['attachments']['type'][$key],
                    'size' => $_FILES['attachments']['size'][$key]
                ];
                $attachment_result = handleFileUpload($attachment_file, 'attachment');
                if (isset($attachment_result['filename'])) {
                    $attachments[] = $attachment_result['filename'];
                }
            }
        }
    }

    // Convert attachments array to JSON for storage
    $attachments_json = json_encode($attachments);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO blogs (title, article, author, position, attachments, banner, time, category, date, slug) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$title, $article, $author, $position, $attachments_json, $banner_path, $time, $category, $date, $slug]);
        $success_message = "Blog post created successfully!";
    } catch (PDOException $e) {
        $error_message = "Error creating blog post: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://sirati.bh/assets/images/fav.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav x-data="{ open: false }"
        class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo Section -->
                <div class="flex items-center">
                    <a href="https://sirati.bh" class="flex-shrink-0 flex items-center">
                        <span
                            class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-600">
                            Sirati <span class="text-gray-500">Blogs</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                    <a href="index.php"
                        class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                        Main
                    </a>
                    <a href="extractor.php"
                        class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                        Extractor
                    </a>
                    <a href="https://support.sirati.bh/ticket_management.php"
                        class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                        Tickets
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="sm:hidden bg-white border-b border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <a href="https://sirati.bh" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                    Home
                </a>
                <a href="https://sirati.bh/register" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                    Register
                </a>
                <a href="https://help.sirati.bh" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                    FAQ
                </a>
                <a href="https://ap.sirati.bh" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                    Businesses
                </a>
            </div>
        </div>
    </nav>
    <div class="pt-20 container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Create New Blog Post</h1>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <!-- Blog Title -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Blog Title
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="title" type="text" name="title" required>
            </div>

            <!-- Slug (English) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                    Slug (English)
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="slug" type="text" name="slug" required>
            </div>


            <!-- Author and Position -->
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="author">
                        Author
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="author" type="text" name="author" required>
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                        Position
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="position" type="text" name="position" required>
                </div>
            </div>

            <!-- Time to Read -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="time">
                    Time to Read (minutes)
                </label>
                <select
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="time" name="time" required>
                    <?php for ($i = 1; $i <= 60; $i += 1): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> min</option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- Category -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                    Category
                </label>
                <select
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="category" name="category" required>
                    <option value="نصائح المقابلة">Interview Tips</option>
                    <option value="أخطاء-السيرة الذاتية">Resume Mistakes</option>
                    <option value="نصائح-الشبكات المهنية">Networking Tips</option>
                    <option value="استراتيجيات-البحث-عن الوظيفة">Job Search Strategies</option>
                    <option value="نمو الوظيفة">Career Growth</option>
                </select>
            </div>
            <!-- Blog Content -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="article">
                    Blog Content
                </label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="article" name="article" rows="10" required></textarea>
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">
                    Publication Date
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="date" type="date" name="date" required>
            </div>

            <!-- Banner Image -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="banner">
                    Banner Image
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="banner" type="file" name="banner" accept="image/*" required>
            </div>

            <!-- Attachments -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="attachments">
                    Attachments (Multiple files allowed)
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="attachments" type="file" name="attachments[]" multiple>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Create Blog Post
                </button>
            </div>
        </form>
    </div>
</body>

</html>