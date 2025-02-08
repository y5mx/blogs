<?php
// index.php
define('IN_INDEX', true);
include 'header.php';
require_once 'config.php';

$items_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total count for pagination
$total_count = $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
$total_pages = ceil($total_count / $items_per_page);

// Get all categories for navigation
$categories = $pdo->query("SELECT DISTINCT category FROM blogs")->fetchAll(PDO::FETCH_COLUMN);

// Get blog posts with pagination
$stmt = $pdo->prepare("SELECT title, article, author, slug, date, category 
                       FROM blogs 
                       ORDER BY date DESC 
                       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'navbar.php'; ?>

<!-- Main Content Wrapper -->
<div class="pt-20 container mx-auto px-4 py-8 flex-grow mt-5">
    <!-- Category Navigation -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-4 justify-center">
            <button class="category-filter px-4 py-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 active"
                data-category="all">
                الكل
            </button>
            <?php foreach ($categories as $category): ?>
                <button class="category-filter px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300"
                    data-category="<?php echo htmlspecialchars($category); ?>">
                    <?php echo htmlspecialchars(ucfirst($category)); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <input type="text" id="search-input" placeholder="البحث في المدونات..."
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="blog-container">
        <?php foreach ($blogs as $blog): ?>
            <a href="/sirati/blogs/<?php echo htmlspecialchars($blog['slug']); ?>"
                class="block blog-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300"
                data-category="<?php echo htmlspecialchars($blog['category']); ?>">
                <article class="p-6">
                    <h3 class="text-2xl font-bold mb-2 hover:text-blue-500 transition-colors">
                        <?php echo htmlspecialchars($blog['title']); ?>
                    </h3>
                    <p class="text-gray-600 mb-4">
                        <?php echo htmlspecialchars(substr($blog['article'], 0, 150)) . '...'; ?>
                    </p>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span><?php echo htmlspecialchars($blog['author']); ?></span>
                        <span><?php echo date('M d, Y', strtotime($blog['date'])); ?></span>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Load More Button -->
    <?php if ($page < $total_pages): ?>
        <div class="text-center mt-8">
            <button id="load-more" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                data-page="<?php echo $page + 1; ?>">
                Load More
            </button>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="assets/js/script.js"></script>