<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employees') }} <span id="total"></span>
                </h2>
                <div id="new"></div>
            </div>
        </x-slot>

        <div class="p-2">
            <div class="mx-auto">
                <div class="p-2 text-gray-900 dark:text-gray-100" x-data="employees">
                    <template x-teleport="#total">
                        <span class="text-xs ms-2 text-[#ac7909]" x-text="totalRecords"></span>
                    </template>

                    <template x-teleport="#new">
                        <button @click="openEmployeeModal()" 
                                class="bg-[#ac7909] hover:bg-[#8e6407] text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Add Employee
                        </button>
                    </template>

                    <!-- Search and Filters -->
                    <div class="mb-6 space-y-4">
                        <!-- Search and Sort Controls -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </span>
                                <input type="text" 
                                       x-model="search" 
                                       @input.debounce.500ms="resetAndFetch()"
                                       placeholder="Search employees..." 
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
                                    <option value="type">Type</option>
                                    <option value="created_at">Creation Date</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div class="flex items-center gap-4 flex-wrap md:flex-nowrap">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                       x-model="typeFilter" 
                                       @change="resetAndFetch()"
                                       value=""
                                       class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">All</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                       x-model="typeFilter" 
                                       @change="resetAndFetch()"
                                       value="engineer"
                                       class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">Engineer</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                       x-model="typeFilter" 
                                       @change="resetAndFetch()"
                                       value="supervisor"
                                       class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">Supervisor</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" 
                                       x-model="typeFilter" 
                                       @change="resetAndFetch()"
                                       value="technician"
                                       class="form-radio text-[#ac7909] focus:ring-[#ac7909] border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-[#ac7909] dark:text-[#ac7909]">Technician</span>
                            </label>
                        </div>
                    </div>

                    <!-- Employees Cards -->
                    <div class="space-y-4">
                        <template x-for="employee in employees" :key="employee.id">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 dark:border-gray-700">
                                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-[#ac7909]/10 to-[#8e6407]/10 dark:from-[#ac7909]/20 dark:to-[#8e6407]/20">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white" x-text="employee.name"></h3>
                                        <span :class="{
                                            'px-3 py-1 rounded-full text-xs font-semibold shadow-sm': true,
                                            'bg-green-100 text-green-800 border border-green-200': employee.is_active,
                                            'bg-red-100 text-red-800 border border-red-200': !employee.is_active
                                        }" x-text="employee.is_active ? 'Active' : 'Inactive'"></span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-user-tag w-5 text-[#ac7909]"></i>
                                            <span class="capitalize" x-text="employee.type"></span>
                                        </p>
                                    </div>

                                    <div class="mt-4 flex justify-end space-x-2">
                                        <button @click="openEmployeeModal(employee)" 
                                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1 rounded-md transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteEmployee(employee.id)" 
                                                class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1 rounded-md transition-all duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

                    <!-- Load more button -->
                    <template x-if="hasMorePages && !isLoading">
                        <div x-cloak x-intersect="loadMore" 
                             class="py-12 text-center">
                            <button @click="loadMore"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#ac7909] hover:bg-[#8e6407] rounded-md shadow-sm">
                                Load More
                            </button>
                        </div>
                    </template>

                    <!-- Employee Form Modal -->
                    <x-modal name="employee-modal" :show="false">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="editingEmployee ? 'Edit Employee' : 'Add Employee'"></h2>
                            <form @submit.prevent="saveEmployee" class="mt-6">
                                <!-- Name -->
                                <div class="mb-4">
                                    <x-input-label for="name" value="Name" />
                                    <x-text-input id="name" type="text" x-model="currentEmployee.name" class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Type -->
                                <div class="mb-4">
                                    <x-input-label for="type" value="Type" />
                                    <select id="type" 
                                            x-model="currentEmployee.type" 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="engineer">Engineer</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="technician">Technician</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" 
                                               x-model="currentEmployee.is_active"
                                               class="rounded border-gray-300 text-[#ac7909] shadow-sm focus:ring-[#ac7909]">
                                        <span class="ml-2 text-gray-600 dark:text-gray-400">Active</span>
                                    </label>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'employee-modal')">
                                        Cancel
                                    </x-secondary-button>

                                    <x-primary-button class="ml-3">
                                        Save
                                    </x-primary-button>
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
        Alpine.data('employees', () => ({
            employees: [],
            currentEmployee: {},
            editingEmployee: false,
            search: '',
            typeFilter: '',
            sortBy: 'name',
            sortDirection: 'asc',
            errors: {},
            currentPage: 1,
            hasMorePages: true,
            isLoading: false,
            perPage: 10,
            totalRecords: 0,

            init() {
                this.fetchEmployees();
            },

            resetAndFetch() {
                this.currentPage = 1;
                this.employees = [];
                this.fetchEmployees();
            },

            loadMore() {
                this.currentPage++;
                this.fetchEmployees();
            },

            fetchEmployees() {
                this.isLoading = true;
                axios.get('/employees', {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage,
                        sort_by: this.sortBy,
                        sort_direction: this.sortDirection,
                        type: this.typeFilter,
                        search: this.search
                    }
                }).then(response => {
                    if (this.currentPage === 1) {
                        this.employees = response.data.data;
                    } else {
                        this.employees = [...this.employees, ...response.data.data];
                    }
                    this.hasMorePages = response.data.current_page < response.data.last_page;
                    this.totalRecords = response.data.total;
                }).catch(error => {
                    console.error('Error fetching employees:', error);
                }).finally(() => {
                    this.isLoading = false;
                });
            },

            openEmployeeModal(employee = null) {
                this.errors = {};
                this.resetForm();
                this.editingEmployee = employee != null;
                
                if (employee) {
                    this.currentEmployee = { ...employee };
                }
                
                this.$dispatch('open-modal', 'employee-modal');
            },

            saveEmployee() {
                this.isLoading = true;
                const method = this.editingEmployee ? 'PUT' : 'POST';
                const url = this.editingEmployee ? `/employees/${this.currentEmployee.id}` : '/employees';

                axios({
                    method: method,
                    url: url,
                    data: this.currentEmployee
                })
                .then(response => {
                    const savedEmployee = response.data;
                    if (this.editingEmployee) {
                        const index = this.employees.findIndex(e => e.id === savedEmployee.id);
                        if (index !== -1) {
                            this.employees[index] = savedEmployee;
                        }
                    } else {
                        this.employees.unshift(savedEmployee);
                    }
                    this.$dispatch('close-modal', 'employee-modal');
                    this.resetForm();
                })
                .catch(error => {
                    if (error.response?.data?.errors) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            deleteEmployee(id) {
                if (!confirm('Are you sure you want to delete this employee?')) return;

                this.isLoading = true;
                axios.delete(`/employees/${id}`)
                    .then(() => {
                        this.employees = this.employees.filter(e => e.id !== id);
                    })
                    .catch(error => {
                        console.error('Error deleting employee:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },

            resetForm() {
                this.editingEmployee = false;
                this.errors = {};
                this.currentEmployee = {
                    name: '',
                    type: 'engineer',
                    is_active: true
                };
            }
        }));
    });
</script> 