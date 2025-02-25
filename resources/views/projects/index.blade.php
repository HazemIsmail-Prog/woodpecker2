<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Projects') }} <span id="total"></span>
            </h2>
            <div id="new"></div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class=" mx-auto">
            <div class="p-2 text-gray-900 dark:text-gray-100" x-data="projects">

                <template x-teleport="#total">
                    <span class="text-xs ms-2 text-[#ac7909]" x-text="totalRecords"></span>
                </template>
                <template x-teleport="#new">
                                        <!-- Add this near your "Add Project" button -->
                <div>
                    <button @click="$dispatch('open-modal', 'import-modal')"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-file-import mr-2"></i>Import Projects
                    </button>
                        <button @click="openProjectModal()"
                                class="bg-[#ac7909] hover:bg-[#8e6407] text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Add Project
                        </button>
                </div>
                </template>

                <!-- Search and Filters -->
                <div class="mb-6 space-y-4">
                    <!-- Search and Sort Controls -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" 
                                    x-model="search" 
                                    @input.debounce.500ms="resetAndFetch()"
                                    placeholder="Search projects..." 
                                    class="w-full pl-10 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ac7909] focus:border-[#ac7909]">
                        </div>

                        <!-- Sort Field -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-sort text-gray-400"></i>
                            </span>
                            <select x-model="sortBy" 
                                    @change="resetAndFetch()"
                                    class="w-full pl-10 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ac7909] focus:border-[#ac7909] appearance-none">
                                <option value="name">Name</option>
                                <option value="quotation_number">Quotation Number</option>
                                <option value="type_of_work">Type of Work</option>
                                <option value="id">Creation Date</option>
                                <option value="contract_date">Contract Date</option>
                                <option value="delivery_date">Delivery Date</option>
                                <option value="installation_date">Installation Date</option>
                                <option value="value">Value</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Sort Direction -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-arrow-up-wide-short text-gray-400"></i>
                            </span>
                            <select x-model="sortDirection" 
                                    @change="resetAndFetch()"
                                    class="w-full pl-10 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ac7909] focus:border-[#ac7909] appearance-none">
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-col md:flex-row md:items-center gap-4 rounded-lg ">
                        <!-- Status Filter -->
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-filter text-gray-400"></i>
                            </span>
                            <select x-model="statusFilter" 
                                    @change="resetAndFetch()"
                                    class="w-full pl-10 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ac7909] focus:border-[#ac7909] appearance-none">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="site_on_hold">Site On Hold</option>
                                <option value="site_not_ready">Site Not Ready</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Schedule Filter -->
                        <div class="flex items-center gap-4 flex-wrap md:flex-nowrap">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                        x-model="schedulesFilter" 
                                        @change="resetAndFetch()"
                                        value=""
                                        class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">All</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                        x-model="schedulesFilter" 
                                        @change="resetAndFetch()"
                                        value="true"
                                        class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">Scheduled</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                        x-model="schedulesFilter" 
                                        @change="resetAndFetch()"
                                        value="false"
                                        class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">Not Scheduled</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Projects Table -->
                <div class="space-y-4">
                    <template x-for="project in projects" :key="project.id">
                        <!-- Project Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 dark:border-gray-700">
                            <!-- Top Section - Enhanced -->
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-[#ac7909]/10 to-[#8e6407]/10 dark:from-[#ac7909]/20 dark:to-[#8e6407]/20">
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
                                            <p 
                                            
                                            class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex">
                                                <i class="fas fa-hashtag text-blue-400 w-5 flex-shrink-0"></i>
                                                <span :class="{'bg-[#ac7909] text-white px-2 py-1 rounded-md dark:bg-[#8e6407]':project.quotation_number === search}" x-text="project.quotation_number"></span>
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
                                        <!-- Manpower Summary -->
                                        <div x-show="project.daily_schedules?.length > 0" class="mt-4 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                                <i class="fas fa-users text-blue-400 mr-2"></i>
                                                Manpower Summary
                                            </h3>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400">Days</span>
                                                    <span class="font-medium text-gray-800 dark:text-gray-200" x-text="project.daily_schedules?.length"></span>
                                                </div>
                                                <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400">Team Leaders & Labours</span>
                                                    <span class="font-medium text-gray-800 dark:text-gray-200" x-text="getSupervisorsCount(project) + getTechniciansCount(project)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Section - Enhanced -->
                                    <div class="w-full lg:w-2/3 lg:pl-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
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
                                        </div>
                                        <!-- display project Schedules -->
                                        <div x-show="project.schedules?.length > 0" class="mt-4">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Schedules</h3>
                                            <div class="mt-2 overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                                        <tr>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Start Date</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">End Date</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progress Value</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                                                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                        <template x-for="schedule in project.schedules" :key="schedule.id">
                                                            <tr>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="formatDate(schedule.start_date)"></td>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="formatDate(schedule.end_date)"></td>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="schedule.duration + ' days'"></td>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="getProgressValue(schedule)"></td>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="schedule.status"></td>
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200" x-text="schedule.notes"></td>
                                                                <!-- delete schedule -->    
                                                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                                                    <button @click="deleteSchedule(schedule)" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1 rounded-md transition-all duration-200">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="flex justify-end mt-4">
                                            <button @click="openScheduleModal(project)" 
                                                    class=" text-[#8e6407] hover:bg-[#8e6407] hover:text-white text-xs py-1 px-2 rounded self-center text-center">
                                                <i class="fas fa-plus mr-2"></i>Add Schedule
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Section - Enhanced -->
                                <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                    <span x-show="project.value" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 rounded-full" x-text="formatCurrency(project.value)"></span>
                                    <span x-show="!project.value" class="text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 px-3 py-1 rounded-full">No Value</span>
                                    <div class="flex space-x-2">
                                        <button @click="openProjectModal(project)" 
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

                <!-- Loading indicator -->
                <div x-show="isLoading" 
                        class="py-4 text-center">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900 dark:border-gray-100"></div>
                </div>


                <template x-if="hasMorePages && !isLoading">
                    <!-- Load more button -->
                    <div x-cloak x-intersect="loadMore" 
                            class="py-12 text-center">
                        <button @click="loadMore"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#ac7909] hover:bg-[#8e6407] rounded-md shadow-sm">
                            Load More
                        </button>
                    </div>
                </template>


                <!-- add scroll to top button -->
                <button x-cloak x-transition @click="scrollToTop" x-show="isScrolled"
                        class="fixed bottom-4 right-4 bg-[#ac7909] text-white px-4 py-3.5 rounded-full shadow-lg hover:bg-[#8e6407] transition-colors duration-200">
                    <i class="fas fa-arrow-up"></i>
                </button>


                @include('projects.project-form-modal')
                @include('projects.schedule-form-modal')
                @include('projects.import-modal')


            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('projects', () => ({
            projects: [],
            employees: @json($employees),
            currentProject: {},
            editingProject: false,
            search: '',
            statusFilter: '',
            schedulesFilter: '',
            sortBy: 'installation_date',
            sortDirection: 'desc',
            errors: {},
            currentPage: 1,
            hasMorePages: true,
            isLoading: false,
            perPage: 10,
            scheduleForm: {
                duration: '',
                status: '',
                notes: ''
            },

            totalRecords: 0,
            isScrolled: false,
            importFile: null,
            hasFile: false,

            init() {
                this.fetchProjects();
                window.addEventListener('scroll', () => {
                    this.isScrolled = window.scrollY > 100;
                });
                console.log(this.employees);
            },

            getProgressValue(schedule) {
                const project = this.projects.find(p => p.id === schedule.project_id);
                const projectValue = project.value;
                if (!projectValue) return 0;
                if(!schedule.start_date || !schedule.end_date) return 0;
                if(new Date(schedule.start_date) > new Date()) return 0;
                const totalDuration = project.schedules?.reduce((acc, curr) => acc + curr.duration, 0);
                const valuePerDay = projectValue / totalDuration;
                const startDate = new Date(schedule.start_date);
                const endDate = new Date();
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays > schedule.duration) return valuePerDay * schedule.duration;
                return valuePerDay * diffDays;

            },

            getSupervisorsCount(project) {
                // get total number of emplyee_id in the project from dailySchedules getting supervisor_id from this.employees
                return project.daily_schedules?.reduce((acc, curr) => acc + curr.employee_ids.filter(id => this.employees.find(e => e.id === id && e.type === 'supervisor')?.id).length, 0);
            },
            getTechniciansCount(project) {
                // get total number of emplyee_id in the project from dailySchedules getting supervisor_id from this.employees
                return project.daily_schedules?.reduce((acc, curr) => acc + curr.employee_ids.filter(id => this.employees.find(e => e.id === id && e.type === 'technician')?.id).length, 0);
            },
            getEngineersCount(project) {
                // get total number of emplyee_id in the project from dailySchedules getting supervisor_id from this.employees
                return project.daily_schedules?.reduce((acc, curr) => acc + curr.employee_ids.filter(id => this.employees.find(e => e.id === id && e.type === 'engineer')?.id).length, 0);
            },

            resetAndFetch() {
                this.currentPage = 1;
                this.projects = [];
                this.fetchProjects();
            },

            deleteSchedule(schedule) {
                if (!confirm('Are you sure you want to delete this schedule?')) return;

                this.isLoading = true;
                axios.delete(`/schedules/${schedule.id}`)
                    .then(() => {
                        const projectIndex = this.projects.findIndex(p => p.id === schedule.project_id);
                        this.projects[projectIndex].schedules = this.projects[projectIndex].schedules?.filter(s => s.id !== schedule.id);
                    })
                    .catch(error => {
                        console.error('Error deleting schedule:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },

            loadMore() {
                this.currentPage++;
                this.fetchProjects();
            },

            setDeliveryDate() {
                if (this.currentProject.contract_date) {
                    const date = new Date(this.currentProject.contract_date);
                    date.setDate(date.getDate() + 70);
                    this.currentProject.delivery_date = date.toISOString().split('T')[0];

                    this.setInstallationDate();
                }
            },

            setInstallationDate() {
                if (this.currentProject.delivery_date) {
                    const date = new Date(this.currentProject.delivery_date);
                    date.setDate(date.getDate() + 10);
                    this.currentProject.installation_date = date.toISOString().split('T')[0];
                }
            },

            openScheduleModal(project) {
                this.currentProject = project;
                this.$dispatch('open-modal', 'schedule-modal');
                this.$nextTick(() => {
                    const durationInput = document.getElementById('duration');
                    if (durationInput) {
                        durationInput.focus();
                    }
                });
            },

            fetchProjects() {
                this.isLoading = true;
                axios.get('/projects', {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage,
                        sort_by: this.sortBy,
                        sort_direction: this.sortDirection,
                        status: this.statusFilter,
                        search: this.search,
                        schedules_filter: this.schedulesFilter
                    }
                }).then(response => {
                    if (this.currentPage === 1) {
                        this.projects = response.data.data;
                    } else {
                        this.projects = [...this.projects, ...response.data.data];
                    }
                    this.hasMorePages = response.data.current_page < response.data.last_page;
                    this.totalRecords = response.data.total;
                }).catch(error => {
                    console.error('Error fetching projects:', error);
                    alert('Failed to load projects. Please refresh the page.');
                }).finally(() => {
                    this.isLoading = false;
                });
            },

            resetScheduleForm() {
                this.scheduleForm = {
                    duration: '',
                    status: '',
                    notes: ''
                };
            },

            saveSchedule() {
                this.errors = {}; // Clear previous errors
                this.isLoading = true;

                axios.post('/schedules/' + this.currentProject.id, this.scheduleForm)
                .then(response => {
                    const savedSchedule = response.data;
                    this.currentProject.schedules?.unshift(savedSchedule);
                    this.$dispatch('close-modal', 'schedule-modal');
                    this.resetScheduleForm();
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error('Error saving schedule:', error);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
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

            saveProject() {
                this.errors = {}; // Clear previous errors
                this.isLoading = true;

                const method = this.editingProject ? 'PUT' : 'POST';
                const url = this.editingProject ? `/projects/${this.currentProject.id}` : '/projects';

                axios({
                    method: method,
                    url: url,
                    data: this.currentProject
                })
                .then(response => {
                    const savedProject = response.data;
                    if (this.editingProject) {
                        const index = this.projects.findIndex(p => p.id === savedProject.id);
                        if (index !== -1) {
                            this.projects[index] = savedProject;
                        }
                    } else {
                        this.projects.unshift(savedProject);
                    }
                    this.$dispatch('close-modal', 'project-modal');
                    this.resetForm();
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error('Error saving project:', error);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            deleteProject(id) {
                if (!confirm('Are you sure you want to delete this project?')) return;

                this.isLoading = true;
                axios.delete(`/projects/${id}`)
                    .then(() => {
                        this.projects = this.projects.filter(p => p.id !== id);
                    })
                    .catch(error => {
                        console.error('Error deleting project:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },

            openProjectModal(project = null) {
                this.errors = {};
                this.resetForm();
                this.editingProject = project != null;

                this.currentProject = project != null ? {
                    ...project,
                    status: project.status == null || project.status == 'Done' ? 'pending' : project.status,
                    contract_date: this.formatDateForInput(project.contract_date),
                    delivery_date: this.formatDateForInput(project.delivery_date),
                    installation_date: this.formatDateForInput(project.installation_date)
                } : {
                    status: 'pending'
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
                    value: '',
                    status: 'pending',
                    notes: ''
                };
            },

            scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            },

            handleFileSelect(event) {
                this.importFile = event.target.files[0];
                this.hasFile = !!this.importFile;
            },

            importProjects() {
                if (!this.importFile) return;

                const formData = new FormData();
                formData.append('file', this.importFile);

                axios.post('/projects/import', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.$dispatch('close-modal', 'import-modal');
                    this.fetchProjects();
                    alert('Projects imported successfully');
                }).catch(error => {
                    console.error('Error importing projects:', error);
                    alert('Error importing projects. Please check the file format and try again.');
                });
            }
        }));
    });
</script>

