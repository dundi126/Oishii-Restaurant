<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu Management - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
        .menu-item { transition: transform 0.3s ease; }
        .menu-item:hover { transform: translateY(-5px); }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Footer at bottom */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }

        /* Fix for category filters scrolling */
        .category-filters {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
        }
        .category-filters::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Edge */
        }

        /* Responsive image fixes */
        .menu-image-container {
            position: relative;
            height: 12rem; /* Fixed height for consistency */
            overflow: hidden;
        }
        .menu-image-container img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        /* Responsive text sizing */
        @media (max-width: 640px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-content {
                margin: 0.5rem;
                max-height: 95vh;
            }

            .admin-controls button {
                width: 100%;
            }

            .menu-title {
                font-size: 1.125rem;
            }

            .menu-price {
                font-size: 1.25rem;
            }

            .menu-description {
                font-size: 0.875rem;
            }
        }

        /* Fix for veg/non-veg badge color classes */
        .veg-badge {
            background-color: #10b981;
        }
        .nonveg-badge {
            background-color: #ef4444;
        }

        /* Customization styles */
        .option-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            background: #f9fafb;
        }
        .option-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
@include('partials.nav')

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-grow w-full">
    <!-- Admin Controls -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button onclick="openModal()" class="bg-japanese-red text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>Add New Item
            </button>
            <button onclick="openCategoryModal()" class="bg-green-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-tags mr-2"></i>Manage Categories
            </button>
            <button onclick="openCustomizationModal()" class="bg-purple-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-cogs mr-2"></i>Manage Customizations
            </button>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8 gap-4">
        <div class="flex items-center w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search menu items..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Category Filters -->
    <div class="category-filters flex flex-nowrap overflow-x-auto pb-4 mb-6 sm:mb-8 gap-3">
        <button class="category-filter px-4 py-3 rounded-full bg-japanese-red text-white font-medium shadow-lg flex-shrink-0" data-category="all">
            All Items
        </button>
        @foreach($categories as $category)
            <button class="category-filter px-4 py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300 flex-shrink-0" data-category="{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" id="menu-grid">
        @foreach($menus as $menu)
            <div class="menu-item bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100" data-category="{{ $menu->category_id }}">
                <div class="menu-image-container bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                    @if($menu->category->icon)
                        <i class="{{ $menu->category->icon }} text-4xl sm:text-6xl text-gray-400 opacity-20"></i>
                    @else
                        <i class="fas fa-utensils text-4xl sm:text-6xl text-gray-400 opacity-20"></i>
                    @endif

                    @if($menu->image_path)
                        <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif

                    <span class="absolute top-3 sm:top-4 right-3 sm:right-4 bg-japanese-red text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                        {{ $menu->category->name }}
                    </span>
                    <span class="absolute top-3 sm:top-4 left-3 sm:left-4 {{ $menu->is_vegetarian ? 'veg-badge' : 'nonveg-badge' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                    </span>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="menu-title text-lg sm:text-xl font-bold text-gray-900">{{ $menu->name }}</h3>
                        <span class="menu-price text-xl sm:text-2xl font-bold text-japanese-red">${{ number_format($menu->price, 2) }}</span>
                    </div>
                    <p class="menu-description text-gray-600 mb-4 text-sm sm:text-base">{{ $menu->description }}</p>

                    <!-- Customizations Badge -->
                    @if($menu->customizations && $menu->customizations->count() > 0)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-cog mr-1"></i>
                                {{ $menu->customizations->count() }} customization(s)
                            </span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-yellow-400">
                            <i class="fas fa-star text-sm"></i>
                            <span class="text-gray-700 font-medium text-sm">{{ $menu->rating ?? '4.5' }}</span>
                            <span class="text-gray-500 text-xs">({{ $menu->review_count ?? '0' }} reviews)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editMenuItem({{ $menu->id }})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="manageCustomizations({{ $menu->id }}, '{{ $menu->name }}')" class="text-purple-600 hover:text-purple-800">
                                <i class="fas fa-cogs"></i>
                            </button>
                            <button onclick="deleteMenuItem({{ $menu->id }})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add/Edit Menu Item Modal -->
    <div id="menuModal" class="modal">
        <div class="modal-content">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900" id="modalTitle">Add New Menu Item</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl sm:text-2xl"></i>
                    </button>
                </div>

                <form id="menuForm" method="POST" enctype="multipart/form-data" action="{{ route('menus.store') }}">
                    @csrf
                    <input type="hidden" id="menuId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                            <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                            <input type="number" name="price" id="price" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category_id" id="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diet Type</label>
                            <select name="is_vegetarian" id="is_vegetarian" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                                <option value="0">Non-Vegetarian</option>
                                <option value="1">Vegetarian</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file" name="image_path" id="image_path" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8">
                        <button type="button" onclick="closeModal()" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 sm:px-6 py-2 bg-japanese-red text-white rounded-lg hover:bg-red-700 transition duration-300 order-1 sm:order-2">
                            Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Category Management Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Manage Categories</h3>
                    <button onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl sm:text-2xl"></i>
                    </button>
                </div>

                <!-- Add Category Form -->
                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Add New Category</h4>
                    <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input type="text" name="icon" placeholder="fas fa-utensils" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <select name="color" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="yellow">Yellow</option>
                                <option value="purple">Purple</option>
                                <option value="pink">Pink</option>
                                <option value="indigo">Indigo</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                                Add Category
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Categories List -->
                <div>
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Existing Categories</h4>
                    <div id="categoriesList" class="space-y-3">
                        @foreach($categories as $category)
                            <div class="flex justify-between items-center p-3 bg-white border border-gray-200 rounded-lg">
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                    <span class="text-sm text-gray-500 sm:ml-2 mt-1 sm:mt-0">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} text-gray-500"></i> •
                                        @endif
                                        {{ ucfirst($category->color) }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editCategory({{ $category->id }})" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customization Management Modal -->
    <div id="customizationModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900" id="customizationModalTitle">Manage Customizations</h3>
                    <button onclick="closeCustomizationModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl sm:text-2xl"></i>
                    </button>
                </div>

                <!-- Menu Item Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Menu Item</label>
                    <select id="menuItemSelect" onchange="loadCustomizations()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                        <option value="">Select a menu item</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->category->name }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Customizations List -->
                <div id="customizationsList" class="space-y-4 mb-6">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <!-- Add Customization Form -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Add New Customization</h4>
                    <form id="customizationForm">
                        @csrf
                        <input type="hidden" id="customizationMenuItemId">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customization Name</label>
                                <input type="text" id="customizationName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent" placeholder="e.g., Spice Level">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select id="customizationType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                                    <option value="select">Single Select</option>
                                    <option value="multiple">Multiple Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="customizationRequired" class="rounded border-gray-300 text-japanese-red focus:ring-japanese-red">
                                <span class="ml-2 text-sm text-gray-700">Required field</span>
                            </label>
                        </div>

                        <!-- Options Section -->
                        <div id="optionsSection">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                            <div id="optionsContainer" class="space-y-2 mb-4">
                                <!-- Options will be added here dynamically -->
                            </div>
                            <button type="button" onclick="addOption()" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-300 transition duration-300 text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Option
                            </button>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeCustomizationModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-japanese-red text-white rounded-lg hover:bg-red-700 transition duration-300">
                                Add Customization
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.footer')

<script>
    // Modal functions
    function openModal() {
        document.getElementById('menuModal').classList.add('active');
        document.getElementById('modalTitle').textContent = 'Add New Menu Item';
        document.getElementById('menuForm').reset();
        document.getElementById('menuId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('menuForm').action = "{{ route('menus.store') }}";
    }

    function closeModal() {
        document.getElementById('menuModal').classList.remove('active');
    }

    function openCategoryModal() {
        document.getElementById('categoryModal').classList.add('active');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.remove('active');
    }

    function openCustomizationModal() {
        document.getElementById('customizationModal').classList.add('active');
        document.getElementById('customizationModalTitle').textContent = 'Manage Customizations';
        document.getElementById('menuItemSelect').value = '';
        document.getElementById('customizationsList').innerHTML = '<p class="text-gray-500 text-center py-4">Select a menu item to manage its customizations.</p>';
        document.getElementById('customizationForm').reset();
        document.getElementById('optionsContainer').innerHTML = '';
    }

    function closeCustomizationModal() {
        document.getElementById('customizationModal').classList.remove('active');
    }

    function manageCustomizations(menuItemId, menuItemName) {
        openCustomizationModal();
        document.getElementById('menuItemSelect').value = menuItemId;
        document.getElementById('customizationModalTitle').textContent = `Customizations for ${menuItemName}`;
        loadCustomizations();
    }

    // Customization management functions
    function loadCustomizations() {
        const menuItemId = document.getElementById('menuItemSelect').value;
        if (!menuItemId) return;

        document.getElementById('customizationMenuItemId').value = menuItemId;

        fetch(`/admin/menu-items/${menuItemId}/customizations`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('customizationsList');
                if (data.customizations && data.customizations.length > 0) {
                    container.innerHTML = data.customizations.map(customization => `
                        <div class="option-item">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">${customization.name}</h4>
                                    <p class="text-sm text-gray-600">
                                        Type: ${customization.type} •
                                        Required: ${customization.required ? 'Yes' : 'No'} •
                                        Options: ${customization.options ? customization.options.length : 0}
                                    </p>
                                    ${customization.options ? `
                                        <div class="mt-2 text-xs text-gray-500">
                                            ${customization.options.map(option =>
                        `${option.value} (${option.price > 0 ? '+' : ''}$${option.price.toFixed(2)})`
                    ).join(', ')}
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="option-actions">
                                    <button onclick="editCustomization(${customization.id})" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteCustomization(${customization.id})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<p class="text-gray-500 text-center py-4">No customizations found for this menu item.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading customizations:', error);
                document.getElementById('customizationsList').innerHTML = '<p class="text-red-500 text-center py-4">Error loading customizations.</p>';
            });
    }

    function addOption() {
        const container = document.getElementById('optionsContainer');
        const optionCount = container.children.length;
        const optionHtml = `
            <div class="flex gap-2 items-center">
                <input type="text" name="options[${optionCount}][value]" placeholder="Option value" required
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                <input type="number" name="options[${optionCount}][price]" placeholder="Price" step="0.01" value="0"
                       class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', optionHtml);
    }

    // Form submission for customizations
    document.getElementById('customizationForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const menuItemId = document.getElementById('customizationMenuItemId').value;

        // Collect options data
        const options = [];
        const optionInputs = document.querySelectorAll('input[name^="options["]');
        for (let i = 0; i < optionInputs.length; i += 2) {
            const valueInput = optionInputs[i];
            const priceInput = optionInputs[i + 1];
            if (valueInput && priceInput && valueInput.value) {
                options.push({
                    value: valueInput.value,
                    price: parseFloat(priceInput.value) || 0
                });
            }
        }

        const data = {
            name: document.getElementById('customizationName').value,
            type: document.getElementById('customizationType').value,
            required: document.getElementById('customizationRequired').checked,
            options: options,
            _token: '{{ csrf_token() }}'
        };

        fetch(`/admin/menu-items/${menuItemId}/customizations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCustomizations();
                    document.getElementById('customizationForm').reset();
                    document.getElementById('optionsContainer').innerHTML = '';
                    alert('Customization added successfully!');
                } else {
                    alert('Error adding customization: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding customization');
            });
    });

    function deleteCustomization(customizationId) {
        if (confirm('Are you sure you want to delete this customization?')) {
            fetch(`/admin/customizations/${customizationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCustomizations();
                        alert('Customization deleted successfully!');
                    } else {
                        alert('Error deleting customization');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting customization');
                });
        }
    }

    // Existing functions
    async function editMenuItem(id) {
        try {
            const response = await fetch(`/menus/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch menu item');

            const data = await response.json();

            document.getElementById('modalTitle').textContent = 'Edit Menu Item';
            document.getElementById('menuId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('price').value = data.price;
            document.getElementById('description').value = data.description;
            document.getElementById('category_id').value = data.category_id;
            document.getElementById('is_vegetarian').value = data.is_vegetarian ? '1' : '0';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('menuForm').action = `/menus/${data.id}`;

            document.getElementById('menuModal').classList.add('active');
        } catch (error) {
            console.error('Error fetching menu item:', error);
            alert('Error loading menu item data');
        }
    }

    function deleteMenuItem(id) {
        if (confirm('Are you sure you want to delete this menu item?')) {
            fetch(`/menus/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error deleting menu item');
                }
            });
        }
    }

    // Category filter functionality
    document.querySelectorAll('.category-filter').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('bg-japanese-red', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            this.classList.add('bg-japanese-red', 'text-white');

            // Filter items
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const menuModal = document.getElementById('menuModal');
        const categoryModal = document.getElementById('categoryModal');
        const customizationModal = document.getElementById('customizationModal');

        if (event.target === menuModal) closeModal();
        if (event.target === categoryModal) closeCategoryModal();
        if (event.target === customizationModal) closeCustomizationModal();
    });

    // Add initial option when page loads
    document.addEventListener('DOMContentLoaded', function() {
        addOption();
    });
</script>
</body>
</html>
