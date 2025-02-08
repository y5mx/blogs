<nav x-data="{ open: false }" class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="https://sirati.bh" class="flex-shrink-0 flex items-center">
                    <span
                        class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-600">
                        المدونات<span class="text-gray-500"></spa>
                        </span>
                </a>
            </div>

            <!-- التنقل لسطح المكتب -->
            <div class="hidden sm:flex sm:items-center sm:mr-6 space-x-4 space-x-reverse">
                <a href="https://sirati.bh"
                    class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                    الرئيسية
                </a>
                <a href="https://sirati.bh/register"
                    class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                    التسجيل
                </a>
                <a href="https://help.sirati.bh"
                    class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                    الأسئلة الشائعة
                </a>
                <a href="https://ap.sirati.bh"
                    class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition-all duration-300 ease-in-out hover:text-blue-600">
                    الأعمال
                </a>
                <div id="google_element"></div>
                <a href="https://sirati.bh/register"
                    class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-800 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
                    ابدأ الآن
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">القائمة الرئيسية</span>
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
                الرئيسية
            </a>
            <a href="https://sirati.bh/register" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                التسجيل
            </a>
            <a href="https://help.sirati.bh" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                الأسئلة الشائعة
            </a>
            <a href="https://ap.sirati.bh" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md">
                الأعمال
            </a>
        </div>
    </div>
</nav>
<!-- Subtle Page Background Gradient -->
<div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-blue-50 opacity-50 -z-10"></div>