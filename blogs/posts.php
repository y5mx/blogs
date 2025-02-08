<?php
require_once 'config.php';

// Get slug from URL
$blog_slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Fetch blog by slug (assuming you store slugs in the database)
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ?");
$stmt->execute([$blog_slug]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

// 404 if blog not found
if (!$blog) {
    include '404.html';
    exit();
}

// Parse attachments
$attachments = json_decode($blog['attachments'], true) ?? [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?> - Sirati Blogs</title>
    <link rel="shortcut icon" href="https://sirati.bh/assets/images/fav.png" type="image/x-icon">
    <!-- Meta Tags -->
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($blog['article']), 0, 160)); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($blog['title']); ?>">
    <meta property="og:description"
        content="<?php echo htmlspecialchars(substr(strip_tags($blog['article']), 0, 160)); ?>">
    <meta property="og:image" content="/uploads/banners/<?php echo htmlspecialchars($blog['banner']); ?>">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="<?php echo $blog['date']; ?>">
    <meta property="article:author" content="<?php echo htmlspecialchars($blog['author']); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="http://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate&hl=en"></script>
    <style>
        * {
            font-family: "IBM Plex Sans Arabic", sans-serif;
            font-weight: bold;
            font-style: normal;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    <!-- Add padding-top to create space below the fixed navbar -->
    <div class="pt-20 container mx-auto max-w-6xl px-4 py-8 grid grid-cols-12 gap-8">
        <!-- Go Back Button -->
        <div class="col-span-12 flex items-center space-x-2 flex-wrap">
            <a href="javascript:history.back()"
                class="flex items-center text-blue-600 hover:text-blue-800 mb-2 sm:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7" />
                </svg>
                <span class="text-sm">الرجوع</span>
            </a>
            <span class="text-sm mb-2 sm:mb-0 mx-2">/ المقال /</span>
            <span class="text-sm mb-2 sm:mb-0 mx-2">
                <?php echo htmlspecialchars($blog['category']); ?>
            </span>
        </div>
        <!-- Main Content Area -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Header Section -->
            <header
                class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl shadow-lg overflow-hidden max-w-4xl mx-auto px-8 py-10 transition-all duration-300 hover:shadow-2xl">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-6 md:space-y-0">
                    <div class="w-full">
                        <!-- Title with enhanced gradient -->
                        <h1
                            class="text-2xl sm:text-4xl lg:text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 mb-6 tracking-tight leading-tight">
                            <?php echo htmlspecialchars($blog['title']); ?>
                        </h1>

                        <!-- Enhanced metadata section -->
                        <div class="flex flex-wrap items-center gap-6 text-gray-700">
                            <!-- Author info with hover card effect -->
                            <div class="flex items-center space-x-3 group">
                                <span class="font-medium text-sm hover:text-blue-600 transition-colors">
                                    <?php echo htmlspecialchars($blog['author']); ?>
                                </span>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-blue-500 transition-transform duration-300 group-hover:scale-110"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Position with enhanced icon -->
                            <div class="flex items-center space-x-3 group">
                                <span class="font-medium text-sm hover:text-indigo-600 transition-colors">
                                    <?php echo htmlspecialchars($blog['position']); ?>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-indigo-500 transition-transform duration-300 group-hover:scale-110"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <!-- Reading time with animated icon -->
                            <div class="flex items-center space-x-3 group">
                                <span class="font-medium text-sm hover:text-emerald-600 transition-colors">
                                    <?php echo htmlspecialchars($blog['time']); ?> دقائق وقت القراءة
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-emerald-500 transition-transform duration-300 group-hover:scale-110"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <!-- Date with modern calendar icon -->
                            <div class="flex items-center space-x-3 group">
                                <time datetime="<?php echo $blog['date']; ?>"
                                    class="font-medium text-sm hover:text-rose-600 transition-colors">
                                    <?php echo date('F j, Y', strtotime($blog['date'])); ?>
                                </time>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-rose-500 transition-transform duration-300 group-hover:scale-110"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <script>
                // Optional interactive JavaScript for hover effects
                document.addEventListener('DOMContentLoaded', () => {
                    const headerElements = document.querySelectorAll('.group');
                    headerElements.forEach(el => {
                        el.addEventListener('mouseenter', (e) => {
                            e.currentTarget.classList.add('animate-pulse');
                        });
                        el.addEventListener('mouseleave', (e) => {
                            e.currentTarget.classList.remove('animate-pulse');
                        });
                    });
                });
            </script>

            <!-- Article Section -->
            <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-300 pb-2">المقال</h1>
            <article class="bg-white rounded-xl shadow-sm p-6 prose prose-lg max-w-none">
                <?php
                // Enhanced content processing
                $content = nl2br(htmlspecialchars($blog['article']));
                $content = preg_replace('/\*\*(.*?)\*\*/s', '<strong class="text-blue-600">$1</strong>', $content);
                $content = preg_replace('/\*(.*?)\*/s', '<em class="text-gray-700">$1</em>', $content);
                $content = preg_replace('/^- (.*?)$/m', '<li class="text-gray-800 mb-2">$1</li>', $content);
                $content = preg_replace('/<li>.*?<\/li>\s+<li>.*?<\/li>/s', '<ul class="list-disc list-inside pl-4">$0</ul>', $content);

                echo $content;
                ?>
            </article>
        </div>

        <!-- Sidebar for Attachments -->
        <aside class="col-span-12 lg:col-span-4 space-y-6">
            <?php if (!empty($attachments)): ?>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">المرفقات</h2>
                    <div class="grid grid-cols-1 gap-4" id="attachments-container">
                        <?php foreach ($attachments as $attachment):
                            $ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            ?>
                            <div class="relative group">
                                <?php if ($isImage): ?>
                                    <img src="uploads/attachments/<?php echo htmlspecialchars($attachment); ?>"
                                        alt="<?php echo htmlspecialchars(basename($attachment)); ?>"
                                        class="w-full h-48 object-cover rounded-lg cursor-pointer transition-transform duration-300 hover:scale-105"
                                        onclick="openImageModal(this.src)" />
                                <?php else: ?>
                                    <a href="uploads/attachments/<?php echo htmlspecialchars($attachment); ?>"
                                        class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors"
                                        target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 mr-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <span class="text-blue-600 hover:text-blue-800">
                                            <?php echo htmlspecialchars(basename($attachment)); ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh]">
            <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[80vh] object-contain">
            <button onclick="closeImageModal()" class="absolute top-0 right-0 m-4 text-white text-4xl">&times;</button>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }
    </script>
</body>

</html>