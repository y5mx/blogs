document.addEventListener("DOMContentLoaded", function () {
  const blogContainer = document.getElementById("blog-container");
  const searchInput = document.getElementById("search-input");
  const categoryFilters = document.querySelectorAll(".category-filter");
  const loadMoreBtn = document.getElementById("load-more");
  let currentCategory = "all";

  // Category filtering
  categoryFilters.forEach((button) => {
    button.addEventListener("click", () => {
      // Update active state
      categoryFilters.forEach((btn) => {
        btn.classList.remove("bg-blue-500", "text-white");
        btn.classList.add("bg-gray-200", "text-gray-700");
      });
      button.classList.remove("bg-gray-200", "text-gray-700");
      button.classList.add("bg-blue-500", "text-white");

      currentCategory = button.dataset.category;
      filterBlogs();
    });
  });

  // Search functionality
  searchInput.addEventListener("input", filterBlogs);

  function filterBlogs() {
    const searchTerm = searchInput.value.toLowerCase();
    const blogCards = document.querySelectorAll(".blog-card");

    blogCards.forEach((card) => {
      const title = card.querySelector("h2").textContent.toLowerCase();
      const content = card.querySelector("p").textContent.toLowerCase();
      const category = card.dataset.category;

      const matchesSearch =
        title.includes(searchTerm) || content.includes(searchTerm);
      const matchesCategory =
        currentCategory === "all" || category === currentCategory;

      if (matchesSearch && matchesCategory) {
        card.style.display = "";
      } else {
        card.style.display = "none";
      }
    });
  }

  // Load more functionality
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", async () => {
      const nextPage = loadMoreBtn.dataset.page;
      try {
        const response = await fetch(`/blog/load-more.php?page=${nextPage}`);
        const data = await response.json();

        if (data.blogs.length > 0) {
          // Append new blogs
          data.blogs.forEach((blog) => {
            const blogHTML = createBlogCard(blog);
            blogContainer.insertAdjacentHTML("beforeend", blogHTML);
          });

          // Update button
          loadMoreBtn.dataset.page = parseInt(nextPage) + 1;
          if (!data.hasMore) {
            loadMoreBtn.style.display = "none";
          }
        }
      } catch (error) {
        console.error("Error loading more blogs:", error);
      }
    });
  }
  function createBlogCard(blog) {
    return `
            <article class="blog-card bg-white rounded-lg shadow-md overflow-hidden" 
                     data-category="${blog.category}">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2">
                        <a href="/blog/${blog.title
                          .toLowerCase()
                          .replace(/ /g, "-")}" 
                           class="hover:text-blue-500 transition-colors">
                            ${blog.title}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4">
                        ${blog.article.substring(0, 150)}...
                    </p>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>${blog.author}</span>
                        <span>${new Date(blog.date).toLocaleDateString(
                          "en-US",
                          {
                            month: "short",
                            day: "numeric",
                            year: "numeric",
                          }
                        )}</span>
                    </div>
                </div>
            </article>
        `;
  }
});