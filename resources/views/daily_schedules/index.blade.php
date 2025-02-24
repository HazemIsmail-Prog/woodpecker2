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

            <template x-teleport="#topSection">
                <div class="flex items-center space-x-4">

                    <div>
                        <!-- add next and previous button to change date -->
                        <button @click="changeDate(-1)" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <input type="date"
                        x-model="selectedDate"
                        @change="fetchDailySchedules"
                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-[#ac7909] focus:border-[#ac7909]">
                        <button @click="changeDate(1)" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <!-- button to delete schedule for today -->
                    <button @click="deleteSchedule" 
                            class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                        Delete Schedule
                    </button>
                    <button @click="saveSchedule" 
                            class="bg-[#ac7909] hover:bg-[#8e6407] text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                        Save Schedule
                    </button>
                    <button @click="downloadPdf" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </button>
                </div>
            </template>

            <div class="grid grid-cols-12 gap-4">
                <!-- Projects List -->
                <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[calc(100vh-200px)] flex flex-col">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Projects</h3>
                    
                    <!-- Projects Search -->
                    <div class="mb-4">
                        <input type="text"
                            x-model="projectSearch"
                            placeholder="Search projects..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md focus:ring-[#ac7909] focus:border-[#ac7909]">
                    </div>

                    <div class="space-y-2 overflow-y-auto hide-scrollbar flex-1">
                        <template x-for="project in filteredProjects" :key="project.id">
                            <div class="p-3 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer focus:ring-[#ac7909] focus:ring-2"
                                 :class="{'bg-[#ac7909]/10 dark:bg-[#ac7909]/20': selectedProject?.id === project.id}"
                                 @click="addProjectToSchedule(project)">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100" x-text="project.name"></h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="project.quotation_number"></p>
                                    <span :class="{
                                        'px-2 py-1 text-xs font-semibold rounded-full mt-1 inline-block': true,
                                        'bg-yellow-100 text-yellow-800': project.status === 'pending',
                                        'bg-blue-100 text-blue-800': project.status === 'in_progress',
                                        'bg-green-100 text-green-800': project.status === 'completed'
                                    }" x-text="project.status"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div class="col-span-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[calc(100vh-200px)] flex flex-col" id="printableArea">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daily Schedule</h3>
                        <p class="text-gray-600 dark:text-gray-400" x-text="formatDate(selectedDate)"></p>
                    </div>
                    <div class="overflow-y-auto hide-scrollbar flex-1">
                        <div x-show="fetchingDailySchedules" class="flex flex-col items-center justify-center space-y-3 p-8">
                            <svg class="animate-spin h-8 w-8 text-[#ac7909]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div x-show="!assignments.length && !fetchingDailySchedules" class="flex flex-col items-center justify-center space-y-3 p-8">
                            <button @click="loadLatestSchedule " 
                                    class="bg-[#ac7909] hover:bg-[#8e6407] text-white px-6 py-3 rounded-md focus:ring-[#ac7909] focus:ring-2 focus:outline-none flex items-center"
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
                        <table x-show="assignments.length > 0 && !fetchingDailySchedules" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SN.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Team Leaders</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Labors</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Engineers</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="(assignment, index) in assignments" :key="index">
                                    <tr
                                    @click="selectProject(getProjectById(assignment.project_id))"
                                     :class="{'bg-[#ac7909]/10 dark:bg-[#ac7909]/20': selectedProject?.id === assignment.project_id}">
                                     <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="index + 1"></span>
                                     </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100" 
                                                      x-text="getProjectName(assignment.project_id)"></span>

                                                <span class="text-xs text-gray-500 dark:text-gray-400" 
                                                      x-text="getProjectQuatationNumber(assignment.project_id)"></span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400" 
                                                      x-text="getProjectLocation(assignment.project_id)"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 dark:text-gray-100" x-text="getSupervisors(assignment.employee_ids)"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 dark:text-gray-100" x-text="getTechnicians(assignment.employee_ids)"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 dark:text-gray-100" x-text="getEngineers(assignment.employee_ids)"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button @click="removeAssignment(index)" 
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Employees List -->
                <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[calc(100vh-200px)] flex flex-col overflow-hidden hide-scrollbar">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            <template x-if="selectedProject">
                                <span>Assign Team for: <span class="text-[#ac7909]" x-text="selectedProject.name"></span></span>
                            </template>
                            <template x-if="!selectedProject">
                                <span>Select a project first</span>
                            </template>
                        </h3>
                    </div>

                    <template x-if="selectedProject">
                        <div class="flex-1 flex flex-col min-h-0">
                            <!-- Employee Search -->
                            <div class="mb-4 flex-shrink-0">
                                <input type="text"
                                    x-model="employeeSearch"
                                    placeholder="Search employees..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md focus:ring-[#ac7909] focus:border-[#ac7909]">
                            </div>

                            <!-- Employee Type Filters -->
                            <div class="mb-4 flex space-x-2 flex-shrink-0">
                                <button @click="employeeTypeFilter = ''" 
                                        :class="{'bg-[#ac7909] text-white': employeeTypeFilter === '', 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100': employeeTypeFilter !== ''}"
                                        class="px-3 py-1 rounded-full text-sm focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                                    All
                                </button>
                                <button @click="employeeTypeFilter = 'supervisor'" 
                                        :class="{'bg-[#ac7909] text-white': employeeTypeFilter === 'supervisor', 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100': employeeTypeFilter !== 'supervisor'}"
                                        class="px-3 py-1 rounded-full text-sm focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                                    Team Leaders
                                </button>
                                <button @click="employeeTypeFilter = 'technician'" 
                                        :class="{'bg-[#ac7909] text-white': employeeTypeFilter === 'technician', 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100': employeeTypeFilter !== 'technician'}"
                                        class="px-3 py-1 rounded-full text-sm focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                                    Labors
                                </button>
                                <button @click="employeeTypeFilter = 'engineer'" 
                                        :class="{'bg-[#ac7909] text-white': employeeTypeFilter === 'engineer', 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100': employeeTypeFilter !== 'engineer'}"
                                        class="px-3 py-1 rounded-full text-sm focus:ring-[#ac7909] focus:ring-2 focus:outline-none">
                                    Engineers
                                </button>
                            </div>

                            <!-- Employees List -->
                            <div class="overflow-y-auto hide-scrollbar flex-1">
                                <div class="space-y-2">
                                    <template x-for="employee in searchedEmployees" :key="employee.id">
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer focus:ring-[#ac7909] focus:ring-2 focus:outline-none"
                                             @click="toggleEmployee(employee)"
                                             :class="{'border-2 border-[#ac7909]': isEmployeeSelected(employee.id)}">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100" x-text="employee.name"></span>
                                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400" x-text="employee.type"></span>
                                                </div>
                                                <i class="fas fa-check text-[#ac7909]" x-show="isEmployeeSelected(employee.id)"></i>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dailySchedules', () => ({

                // Initialize data with tomorrow's date
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

                init() {
                    // if url /daily-schedules?date=2025-02-21 has date then set selected date to that date
                    if(window.location.search.includes('date')){
                        this.selectedDate = window.location.search.split('=')[1];
                    }
                    this.fetchDailySchedules();
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
                        return this.availableProjects;
                    }
                    const searchTerm = this.projectSearch.toLowerCase();
                    return this.availableProjects.filter(p => {
                        return p.name?.toLowerCase().includes(searchTerm) ||
                               p.location?.toLowerCase().includes(searchTerm) ||
                               p.quotation_number?.toLowerCase().includes(searchTerm) &&
                               !this.assignments.some(a => a.project_id === p.id);
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

                get availableProjects() {
                    return this.projects.filter(p => 
                        !this.assignments.some(a => a.project_id === p.id) &&
                        !['completed', 'cancelled'].includes(p.status)
                    );
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

                    // remove project from available projects then add it to assignments
                    this.availableProjects = this.availableProjects.filter(p => p.id !== project.id);
                    this.assignments.push({
                        project_id: project.id,
                        employee_ids: []
                    });

                    // this.selectedProject = project;
                },

                selectProject(project) {
                    this.selectedProject = project;
                },

                toggleEmployee(employee) {
                    if (!this.selectedProject) return;
                    this.assignments.forEach(assignment => {
                        if(assignment.project_id === this.selectedProject.id){
                            if(assignment.employee_ids.includes(employee.id)){
                                assignment.employee_ids = assignment.employee_ids.filter(id => id !== employee.id);
                            }else{
                                assignment.employee_ids.push(employee.id);
                            }
                        }
                    });
                },

                isEmployeeSelected(employeeId) {
                    return this.assignments.some(assignment => 
                        assignment.project_id === this.selectedProject.id && 
                        assignment.employee_ids.includes(employeeId)
                    );
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
                        .filter(e => employeeIds.includes(e.id) && e.type === 'supervisor')
                        .map(e => e.name)
                        .join(', ');
                },

                getEngineers(employeeIds) {
                    return this.employees
                        .filter(e => employeeIds.includes(e.id) && e.type === 'engineer')
                        .map(e => e.name)
                        .join(', ');
                },

                getTechnicians(employeeIds) {
                    return this.employees
                        .filter(e => employeeIds.includes(e.id) && e.type === 'technician')
                        .map(e => e.name)
                        .join(', ');
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
                }
            }));
        });
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