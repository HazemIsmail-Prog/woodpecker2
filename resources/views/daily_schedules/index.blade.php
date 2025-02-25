<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daily Schedules') }}
            </h2>
            <div id="topSection"></div>
        </div>
    </x-slot>

    <div class="p-4 bg-gray-100 dark:bg-gray-900" x-data="dailySchedules">

        <!-- Unsaved Changes Alert -->
        <div x-cloak x-show="hasUnsavedChanges" 
             class="fixed bottom-4 right-4 bg-[#ac7909] text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-bounce"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <i class="fas fa-exclamation-circle"></i>
            <span>You have unsaved changes!</span>
        </div>

        <!-- Date Selection -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
                <button @click="changeDate(-1)" class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <input type="date"
                    x-model="selectedDate"
                    @change="fetchDailySchedules"
                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-[#ac7909] focus:border-[#ac7909] transition-colors">
                <button @click="changeDate(1)" class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                <button @click="deleteSchedule" 
                    class="flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Schedule
                </button>
                <button @click="saveSchedule" 
                    class="flex items-center bg-[#ac7909] hover:bg-[#8e6407] text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Schedule
                </button>
                <button @click="downloadPdf" 
                    class="flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Download PDF
                </button>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col" id="printableArea">
            <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Daily Schedule</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1" x-text="formatDate(selectedDate)"></p>
                </div>
                <button @click="$dispatch('open-modal', 'projects-modal')" 
                    class="flex items-center bg-[#ac7909] hover:bg-[#8e6407] text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Project
                </button>
            </div>

            <div class="overflow-y-auto hide-scrollbar flex-1">
                <!-- Loading State -->
                <div x-show="fetchingDailySchedules" class="flex flex-col items-center justify-center space-y-3 p-8">
                    <svg class="animate-spin h-10 w-10 text-[#ac7909]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">Loading schedule...</p>
                </div>

                <!-- Empty State -->
                <div x-show="!assignments.length && !fetchingDailySchedules" class="flex flex-col items-center justify-center space-y-4">
                    <div class="text-gray-400 dark:text-gray-500">
                        <i class="fas fa-calendar-times text-6xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">No Schedule Found</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-center">
                        There are no projects scheduled for this date.
                    </p>
                    <button @click="loadLatestSchedule" 
                        class="bg-[#ac7909] hover:bg-[#8e6407] text-white px-6 py-3 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none transition-colors flex items-center"
                        :disabled="isLoading">
                        <template x-if="!isLoading">
                            <i class="fas fa-calendar-check mr-2"></i>
                        </template>
                        <template x-if="isLoading">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        Load Latest Project List
                    </button>
                    <p class="text-gray-500 dark:text-gray-400 text-sm italic">
                        This will only load the latest project list without saving
                    </p>
                </div>

                <!-- Schedule Cards -->
                <div x-show="assignments.length > 0 && !fetchingDailySchedules" 
                     class="flex flex-col gap-4">
                    <template x-for="(assignment, index) in assignments" :key="index">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden transition-colors"
                            :class="{'ring-2 ring-[#ac7909]': selectedProject?.id === assignment.project_id}">
                            
                            <!-- Header Section -->
                            <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#ac7909] text-white font-medium" x-text="index + 1"></span>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="getProjectName(assignment.project_id)"></h3>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-hashtag text-xs"></i>
                                                    <span x-text="getProjectQuatationNumber(assignment.project_id)"></span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                                    <span x-text="getProjectLocation(assignment.project_id)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click.stop="removeAssignment(index)" 
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Team Section -->
                            <div class="grid md:grid-cols-3 divide-x divide-gray-200 dark:divide-gray-700">
                                <!-- Team Leaders -->
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-user-tie text-[#ac7909]"></i>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Team Leaders</h4>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="employee in getSupervisors(assignment.employee_ids)" :key="employee.id">
                                            <span @click="removeEmployeeFromAssignments(assignment.project_id, employee.id)" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-[#ac7909]/10 text-[#ac7909] dark:bg-[#ac7909]/20 hover:bg-[#ac7909]/20 transition-colors cursor-pointer">
                                                <span x-text="getEmployeeName(employee.id)"></span>
                                                <i class="fas fa-times ml-2"></i>
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Labors -->
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-hard-hat text-[#ac7909]"></i>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Labors</h4>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="employee in getTechnicians(assignment.employee_ids)" :key="employee.id">
                                            <span @click="removeEmployeeFromAssignments(assignment.project_id, employee.id)" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-[#ac7909]/10 text-[#ac7909] dark:bg-[#ac7909]/20 hover:bg-[#ac7909]/20 transition-colors cursor-pointer">
                                                <span x-text="getEmployeeName(employee.id)"></span>
                                                <i class="fas fa-times ml-2"></i>
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Engineers -->
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-user-cog text-[#ac7909]"></i>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Engineers</h4>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="employee in getEngineers(assignment.employee_ids)" :key="employee.id">
                                            <span @click="removeEmployeeFromAssignments(assignment.project_id, employee.id)" 
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-[#ac7909]/10 text-[#ac7909] dark:bg-[#ac7909]/20 hover:bg-[#ac7909]/20 transition-colors cursor-pointer">
                                                <span x-text="getEmployeeName(employee.id)"></span>
                                                <i class="fas fa-times ml-2"></i>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Employee Button -->
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <button @click="selectProject(getProjectById(assignment.project_id)); openEmployeeModal()" 
                                    class="w-full flex items-center justify-center px-4 py-2 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:border-[#ac7909] hover:text-[#ac7909] transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Employee
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @include('daily_schedules.modals.projects-modal')
        @include('daily_schedules.modals.employees-modal')
    </div>

    <script>
        function dailySchedules(){
            return {
                selectedDate: new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0],
                employees: @json($employees),
                projects: @json($projects),
                dailySchedules: [],
                selectedProject: null,
                employeeTypeFilter: '',
                assignments: [],
                projectSearch: '',
                employeeSearch: '',
                isLoading: false,
                fetchingDailySchedules: false,
                hasUnsavedChanges: false,
                originalAssignments: [],

                init() {
                    if(window.location.search.includes('date')){
                        this.selectedDate = window.location.search.split('=')[1];
                    }
                    this.fetchDailySchedules();

                    // Watch for changes in assignments
                    this.$watch('assignments', (value) => {
                        this.checkForChanges();
                    }, { deep: true });
                },

                loadLatestSchedule() {
                    this.isLoading = true;
                    axios.get('/daily-schedules/latest')
                        .then(response => {
                            this.dailySchedules = response.data;
                            this.loadExistingSchedule();
                            this.isLoading = false;
                        });
                },

                get filteredProjects() {
                    if (!this.projectSearch) {
                        return this.projects;
                    }
                    const searchTerm = this.projectSearch.toLowerCase();
                    return this.projects.filter(p => {
                        return p.name?.toLowerCase().includes(searchTerm) ||
                               p.location?.toLowerCase().includes(searchTerm) ||
                               p.quotation_number?.toLowerCase().includes(searchTerm);
                    });
                },

                get searchedEmployees() {
                    return this.filteredEmployees.filter(e =>
                        e.name.toLowerCase().includes(this.employeeSearch.toLowerCase())
                    );
                },

                fetchDailySchedules() {
                    this.dailySchedules = [];
                    this.assignments = [];
                    this.selectedProject = null;
                    this.fetchingDailySchedules = true;
                    axios.get('/daily-schedules?date=' + this.selectedDate)
                        .then(response => {
                            this.dailySchedules = response.data;
                            console.log(this.dailySchedules);
                            this.loadExistingSchedule();
                            // Store original state for comparison
                            this.originalAssignments = JSON.parse(JSON.stringify(this.assignments));
                            this.hasUnsavedChanges = false;
                        })
                        .catch(error => {
                            console.error('Error fetching daily schedules:', error);
                        })
                        .finally(() => {
                            this.fetchingDailySchedules = false;
                        });
                },

                loadExistingSchedule() {
                    if (this.dailySchedules) {
                        this.assignments = this.dailySchedules.map(schedule => ({
                            project_id: schedule.project_id,
                            employee_ids: schedule.employee_ids
                        }));
                    }
                },

                deleteSchedule() {
                    if (confirm('Are you sure you want to delete the schedule for today?')) {
                        axios.delete('/daily-schedules?date=' + this.selectedDate)
                            .then(response => {
                                this.fetchDailySchedules();
                            });
                    }
                },

                toggleProject(project) {
                    const existingIndex = this.assignments.findIndex(a => a.project_id === project.id);
                    if (existingIndex >= 0) {
                        this.assignments.splice(existingIndex, 1);
                        if (this.selectedProject?.id === project.id) {
                            this.selectedProject = null;
                        }
                    } else {
                        // Initialize with empty employee_ids array
                        this.assignments.push({
                            project_id: project.id,
                            employee_ids: []  // Ensure this is initialized
                        });
                        this.selectedProject = project;
                    }
                },

                get filteredEmployees() {
                    return this.employees.filter(e => 
                        e.is_active && 
                        (!this.employeeTypeFilter || e.type === this.employeeTypeFilter)
                    );
                },

                changeDate(direction) {
                    const date = new Date(this.selectedDate);
                    date.setDate(date.getDate() + direction);
                    this.selectedDate = date.toISOString().split('T')[0];
                    this.fetchDailySchedules();
                },

                addProjectToSchedule(project) {
                    this.assignments.push({
                        project_id: project.id,
                        employee_ids: []
                    });
                },

                selectProject(project) {
                    this.selectedProject = project;
                    this.openEmployeeModal();
                },

                openEmployeeModal() {
                    this.$dispatch('open-modal', 'employee-modal')
                },

                toggleEmployee(employee) {
                    if (!this.selectedProject) return;

                    const assignment = this.assignments.find(a => a.project_id === this.selectedProject.id);
                    if (!assignment) return;

                    // Initialize employee_ids if it doesn't exist
                    if (!assignment.employee_ids) {
                        assignment.employee_ids = [];
                    }

                    const index = assignment.employee_ids.indexOf(employee.id);
                    if (index >= 0) {
                        assignment.employee_ids.splice(index, 1);
                    } else {
                        assignment.employee_ids.push(employee.id);
                    }
                },

                isEmployeeSelected(employeeId) {
                    return this.assignments.some(assignment => 
                        assignment.project_id === this.selectedProject.id && 
                        assignment.employee_ids.includes(employeeId)
                    );
                },

                isProjectSelected(projectId) {
                    return this.assignments.some(assignment => 
                        assignment.project_id === projectId
                    );
                },

                removeEmployeeFromAssignments(projectId, employeeId){
                    this.assignments.forEach(assignment => {
                        if(assignment.project_id === projectId){
                            assignment.employee_ids = assignment.employee_ids.filter(id => id !== employeeId);
                        }
                    });
                },

                removeAssignment(index) {
                    this.assignments.splice(index, 1);
                    this.selectedProject = null;
                },

                saveSchedule() {
                    axios.post('/daily-schedules', {
                        date: this.selectedDate,
                        assignments: this.assignments
                    })
                    .then(response => {
                        this.fetchDailySchedules();
                        this.hasUnsavedChanges = false;
                        alert('Schedule saved successfully');
                    })
                    .catch(error => {
                        console.error('Error saving schedule:', error);
                        alert('Failed to save schedule');
                    });
                },

                getProjectById(projectId) {
                    return this.projects.find(p => p.id === projectId);
                },

                getProjectName(projectId) {
                    return this.projects.find(p => p.id === projectId)?.name || '';
                },

                getProjectQuatationNumber(projectId) {
                    return this.projects.find(p => p.id === projectId)?.quotation_number || '';
                },

                getProjectLocation(projectId) {
                    return this.projects.find(p => p.id === projectId)?.location || '';
                },

                getSupervisors(employeeIds) {
                    return this.employees
                        .filter(e => employeeIds.includes(e.id) && e.type === 'supervisor');
                },

                getEngineers(employeeIds) {
                    return this.employees
                        .filter(e => employeeIds.includes(e.id) && e.type === 'engineer');
                },

                getTechnicians(employeeIds) {
                    return this.employees
                        .filter(e => employeeIds.includes(e.id) && e.type === 'technician');
                },

                getEmployeeName(employeeId) {
                    return this.employees.find(e => e.id === employeeId)?.name || '';
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                downloadPdf() {
                    window.location.href = `/daily-schedules/pdf?date=${this.selectedDate}`;
                },

                checkForChanges() {
                    if (!this.originalAssignments.length) {
                        this.hasUnsavedChanges = this.assignments.length > 0;
                        return;
                    }
                    
                    this.hasUnsavedChanges = JSON.stringify(this.assignments) !== JSON.stringify(this.originalAssignments);
                }
            };
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</x-app-layout>