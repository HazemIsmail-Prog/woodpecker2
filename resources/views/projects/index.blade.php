<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects') }} <span class="text-sm text-gray-500 dark:text-gray-400">({{ $projects->count() }})</span>
                </h2>
                <div id="new"></div>
            </div>
        </x-slot>

        <div class="p-2">
            <div class="max-w-7xl mx-auto">
                <div class="p-2 text-gray-900 dark:text-gray-100" x-data="projects">

                    <template x-teleport="#new">
                        <button @click="$dispatch('open-modal', 'project-modal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>Add Project
                    </button>

                    </template>

                    <!-- Search and Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" 
                                   x-model="search" 
                                   placeholder="Search projects..." 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select x-model="statusFilter" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <select x-model="sortBy" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="delivery_date">Sort by Delivery Date</option>
                                <option value="contract_date">Sort by Contract Date</option>
                                <option value="value">Sort by Value</option>
                                <option value="name">Sort by Name</option>
                            </select>
                        </div>
                    </div>

                    <!-- Projects Table -->
                    <div class="space-y-4">
                        <template x-for="project in filteredProjects" :key="project.id">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 dark:border-gray-700">
                                <!-- Top Section - Enhanced -->
                                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white hover:text-blue-600 transition-colors duration-200" x-text="project.name"></h3>
                                        <span :class="{
                                            'px-3 py-1 rounded-full text-xs font-semibold shadow-sm': true,
                                            'bg-yellow-100 text-yellow-800 border border-yellow-200': project.status === 'pending',
                                            'bg-blue-100 text-blue-800 border border-blue-200': project.status === 'in_progress',
                                            'bg-green-100 text-green-800 border border-green-200': project.status === 'completed',
                                            'bg-red-100 text-red-800 border border-red-200': project.status === 'cancelled'
                                        }" x-text="project.status"></span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <!-- Main Content Section -->
                                    <div class="flex flex-col lg:flex-row">
                                        <!-- Left Section - Enhanced -->
                                        <div class="w-full lg:w-1/3 lg:pr-6 lg:border-r border-gray-100 dark:border-gray-700 mb-4 lg:mb-0">
                                            <template x-if="project.quotation_number">
                                            <p class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex">
                                                <i class="fas fa-hashtag text-blue-400 w-5 flex-shrink-0"></i>
                                                    <span class="flex-1" x-text="project.quotation_number"></span>
                                                </p>
                                            </template>
                                            <template x-if="project.phone">
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex">
                                                    <i class="fas fa-phone text-green-400 w-5 flex-shrink-0"></i>
                                                    <span class="flex-1" x-text="project.phone"></span>
                                                </p>
                                            </template>
                                            <template x-if="project.location">
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex">
                                                    <i class="fas fa-map-marker-alt text-red-400 w-5 flex-shrink-0"></i>
                                                    <span class="flex-1" x-text="project.location"></span>
                                                </p>
                                            </template>
                                            <template x-if="project.type_of_work">
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex">
                                                    <i class="fas fa-tools text-indigo-400 w-5 flex-shrink-0"></i>
                                                    <span class="flex-1" x-text="project.type_of_work"></span>
                                                </p>
                                            </template>
                                        </div>

                                        <!-- Right Section - Enhanced -->
                                        <div class="w-full lg:w-2/3 lg:pl-6">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Contract Date</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="formatDate(project.contract_date)"></p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Delivery Date</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="formatDate(project.delivery_date)"></p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Installation Date</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="formatDate(project.installation_date)"></p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Duration</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="project.duration ? project.duration + ' days' : '-'"></p>
                                                </div>
                                                <div x-show="project.schedule" class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Start Date</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="project.schedule?.start_date ? formatDate(project.schedule.start_date) : '-'"></p>
                                                </div>
                                                <div x-show="project.schedule" class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">End Date</p>
                                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="project.schedule?.end_date ? formatDate(project.schedule.end_date) : '-'"></p>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bottom Section - Enhanced -->
                                    <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                        <span x-show="project.value" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 rounded-full" x-text="formatCurrency(project.value)"></span>
                                        <span x-show="!project.value" class="text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 px-3 py-1 rounded-full">No Value</span>
                                        <div class="flex space-x-2">
                                            <button @click="editProject(project)" 
                                                    class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1 rounded-md transition-all duration-200">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button @click="deleteProject(project.id)" 
                                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1 rounded-md transition-all duration-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Project Modal -->
                    <x-modal name="project-modal" :show="false">
                        <div class="p-6 dark:bg-gray-800">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="editingProject ? 'Edit Project' : 'Add New Project'"></h2>
                            <form @submit.prevent="saveProject" class="mt-6">
                                <!-- Update all form dividers -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="bg-white dark:bg-gray-800 px-3 text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-info-circle mr-1"></i>Contact Details
                                        </span>
                                    </div>
                                </div>

                                <!-- First Row: Name and Quotation -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                        <input type="text" 
                                               x-model="currentProject.name" 
                                               :class="{'border-red-500': errors.name}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.name" x-text="errors.name" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quotation Number</label>
                                        <input type="text" 
                                               x-model="currentProject.quotation_number" 
                                               :class="{'border-red-500': errors.quotation_number}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.quotation_number" x-text="errors.quotation_number" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                <!-- Second Row: Phone and Location -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                        <input type="tel" 
                                               x-model="currentProject.phone"
                                               :class="{'border-red-500': errors.phone}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                        <input type="text" 
                                               x-model="currentProject.location"
                                               :class="{'border-red-500': errors.location}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.location" x-text="errors.location" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                <!-- Update the Dates section divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="bg-white dark:bg-gray-800 px-3 text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>Dates
                                        </span>
                                    </div>
                                </div>

                                <!-- Third Row: Dates -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contract Date</label>
                                        <input type="date" 
                                               x-model="currentProject.contract_date"
                                               @change="setDeliveryDate"
                                               :class="{'border-red-500': errors.contract_date}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.contract_date" x-text="errors.contract_date" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Date</label>
                                        <input type="date" 
                                               x-model="currentProject.delivery_date"
                                               :class="{'border-red-500': errors.delivery_date}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.delivery_date" x-text="errors.delivery_date" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Installation Date</label>
                                        <input type="date" 
                                               x-model="currentProject.installation_date"
                                               :class="{'border-red-500': errors.installation_date}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.installation_date" x-text="errors.installation_date" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                <!-- Update the Work Details section divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="bg-white dark:bg-gray-800 px-3 text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-cog mr-1"></i>Work Details
                                        </span>
                                    </div>
                                </div>

                                <!-- Fourth Row: Type of Work and Duration -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type of Work</label>
                                        <input type="text" 
                                               x-model="currentProject.type_of_work"
                                               :class="{'border-red-500': errors.type_of_work}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.type_of_work" x-text="errors.type_of_work" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (days)</label>
                                        <input type="number" 
                                               x-model="currentProject.duration"
                                               :class="{'border-red-500': errors.duration}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.duration" x-text="errors.duration" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                <!-- Update the Status & Value section divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="bg-white dark:bg-gray-800 px-3 text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-chart-line mr-1"></i>Status & Value
                                        </span>
                                    </div>
                                </div>

                                <!-- Fifth Row: Value and Status -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value (KD)</label>
                                        <input type="number" 
                                               x-model="currentProject.value"
                                               step="0.01"
                                               :class="{'border-red-500': errors.value}"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <p x-show="errors.value" x-text="errors.value" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select x-model="currentProject.status" 
                                                :class="{'border-red-500': errors.status}"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="pending" class="dark:bg-gray-700">Pending</option>
                                            <option value="in_progress" class="dark:bg-gray-700">In Progress</option>
                                            <option value="completed" class="dark:bg-gray-700">Completed</option>
                                            <option value="cancelled" class="dark:bg-gray-700">Cancelled</option>
                                        </select>
                                        <p x-show="errors.status" x-text="errors.status" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="$dispatch('close')" 
                                            class="bg-white dark:bg-gray-700 dark:text-gray-300 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                </div>
            </div>
        </div>
    </x-app-layout>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('projects', () => ({
            projects: [],
            currentProject: {},
            editingProject: false,
            search: '',
            statusFilter: '',
            sortBy: 'delivery_date',
            errors: {},

            init() {
                this.fetchProjects();
            },

            setDeliveryDate() {
                if (this.currentProject.contract_date) {
                    const date = new Date(this.currentProject.contract_date);
                    date.setDate(date.getDate() + 70);
                    // Format the date as YYYY-MM-DD
                    this.currentProject.delivery_date = date.toISOString().split('T')[0];

                    // set installation date to 10 days from delivery date
                    const installationDate = new Date(this.currentProject.delivery_date);
                    installationDate.setDate(installationDate.getDate() + 10);
                    this.currentProject.installation_date = installationDate.toISOString().split('T')[0];
                }
            },

            async fetchProjects() {
                try {
                    const response = await fetch('/projects', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    this.projects = await response.json();
                } catch (error) {
                    console.error('Error fetching projects:', error);
                    alert('Failed to load projects. Please refresh the page.');
                }
            },

            get filteredProjects() {
                return this.projects
                    .filter(project => {
                        const matchesSearch = project.name?.toLowerCase().includes(this.search.toLowerCase()) ||
                                            project.location?.toLowerCase().includes(this.search.toLowerCase()) ||
                                            project.quotation_number?.includes(this.search);
                        const matchesStatus = !this.statusFilter || project.status === this.statusFilter;
                        return matchesSearch && matchesStatus;
                    })
                    .sort((a, b) => {
                        if (this.sortBy === 'value') {
                            return (b.value || 0) - (a.value || 0);
                        }
                        return new Date(b[this.sortBy]) - new Date(a[this.sortBy]);
                    });
            },

            formatDate(date) {
                return date ? new Date(date).toLocaleDateString() : '-';
            },

            formatCurrency(value) {
                if (!value) return '-';
                return `KD ${parseFloat(value).toLocaleString('en-US', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                })}`;
            },

            async saveProject() {
                this.errors = {}; // Clear previous errors
                try {
                    const url = this.editingProject 
                        ? `/projects/${this.currentProject.id}`
                        : '/projects';
                    
                    const formData = {
                        ...this.currentProject,
                        _method: this.editingProject ? 'PUT' : 'POST'
                    };
                    
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        if (response.status === 422) {
                            this.errors = data.errors;
                            return;
                        }
                        throw new Error('Failed to save project');
                    }

                    const savedProject = await response.json();

                    if (this.editingProject) {
                        const index = this.projects.findIndex(p => p.id === savedProject.id);
                        if (index !== -1) this.projects[index] = savedProject;
                    } else {
                        this.projects.unshift(savedProject);
                    }

                    this.$dispatch('close-modal', 'project-modal');
                    this.resetForm();
                } catch (error) {
                    console.error('Error saving project:', error);
                    alert('Failed to save project. Please try again.');
                }
            },

            async deleteProject(id) {
                if (!confirm('Are you sure you want to delete this project?')) return;

                try {
                    const response = await fetch(`/projects/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error('Failed to delete project');

                    this.projects = this.projects.filter(p => p.id !== id);
                } catch (error) {
                    console.error('Error deleting project:', error);
                    alert('Failed to delete project. Please try again.');
                }
            },

            editProject(project) {
                this.editingProject = true;
                this.currentProject = {
                    ...project,
                    contract_date: this.formatDateForInput(project.contract_date),
                    delivery_date: this.formatDateForInput(project.delivery_date),
                    installation_date: this.formatDateForInput(project.installation_date)
                };
                this.$dispatch('open-modal', 'project-modal');
            },

            formatDateForInput(date) {
                if (!date) return '';
                const d = new Date(date);
                if (isNaN(d.getTime())) return '';
                return d.toISOString().split('T')[0];
            },

            resetForm() {
                this.editingProject = false;
                this.errors = {};  // Clear errors when resetting form
                this.currentProject = {
                    id: null,
                    name: '',
                    contract_date: '',
                    phone: '',
                    location: '',
                    quotation_number: '',
                    delivery_date: '',
                    installation_date: '',
                    type_of_work: '',
                    duration: '',
                    value: '',
                    status: 'pending'
                };
            }
        }));
    });
</script>