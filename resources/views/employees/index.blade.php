<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employees') }} <span class="text-sm text-gray-500 dark:text-gray-400">({{ $employees->count() }})</span>
                </h2>
                <div id="new"></div>
            </div>
        </x-slot>

        <div class="p-2">
            <div class="max-w-7xl mx-auto">
                <div class="p-2 text-gray-900 dark:text-gray-100" x-data="employees">
                    <template x-teleport="#new">
                        <button @click="$dispatch('open-modal', 'employee-modal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Add Employee
                        </button>
                    </template>

                    <!-- Search and Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" 
                                   x-model="search" 
                                   placeholder="Search employees..." 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select x-model="typeFilter" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">All Types</option>
                                <option value="engineer">Engineer</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="technician">Technician</option>
                            </select>
                        </div>
                        <div>
                            <select x-model="statusFilter" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Employees Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="employee in filteredEmployees" :key="employee.id">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 dark:border-gray-700">
                                <!-- Employee Card Header -->
                                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white" x-text="employee.name"></h3>
                                        <span :class="{
                                            'px-3 py-1 rounded-full text-xs font-semibold': true,
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': employee.is_active,
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': !employee.is_active
                                        }" x-text="employee.is_active ? 'Active' : 'Inactive'"></span>
                                    </div>
                                </div>

                                <!-- Employee Card Body -->
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-user-tie text-blue-500 w-5"></i>
                                        <span class="ml-2 text-gray-600 dark:text-gray-300" x-text="capitalizeFirst(employee.type)"></span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <button @click="editEmployee(employee)" 
                                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900 px-3 py-1 rounded-md transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteEmployee(employee.id)" 
                                                class="text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900 px-3 py-1 rounded-md transition-all duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Employee Modal -->
                    <x-modal name="employee-modal" :show="false">
                        <div class="p-6 dark:bg-gray-800">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="editingEmployee ? 'Edit Employee' : 'Add New Employee'"></h2>
                            <form @submit.prevent="saveEmployee" class="mt-6">
                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input type="text" 
                                           x-model="currentEmployee.name" 
                                           :class="{'border-red-500': errors.name}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <p x-show="errors.name" x-text="errors.name" class="mt-1 text-sm text-red-600"></p>
                                </div>

                                <!-- Type -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                    <select x-model="currentEmployee.type" 
                                            :class="{'border-red-500': errors.type}"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="engineer">Engineer</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="technician">Technician</option>
                                    </select>
                                    <p x-show="errors.type" x-text="errors.type" class="mt-1 text-sm text-red-600"></p>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="currentEmployee.is_active"
                                               class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
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
        Alpine.data('employees', () => ({
            employees: [],
            currentEmployee: {},
            editingEmployee: false,
            search: '',
            typeFilter: '',
            statusFilter: '',
            errors: {},

            init() {
                this.fetchEmployees();
            },

            async fetchEmployees() {
                try {
                    const response = await fetch('/employees', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    this.employees = await response.json();
                } catch (error) {
                    console.error('Error fetching employees:', error);
                    alert('Failed to load employees. Please refresh the page.');
                }
            },

            get filteredEmployees() {
                return this.employees
                    .filter(employee => {
                        const matchesSearch = employee.name?.toLowerCase().includes(this.search.toLowerCase());
                        const matchesType = !this.typeFilter || employee.type === this.typeFilter;
                        const matchesStatus = this.statusFilter === '' || 
                            (this.statusFilter === '1' && employee.is_active) || 
                            (this.statusFilter === '0' && !employee.is_active);
                        return matchesSearch && matchesType && matchesStatus;
                    });
            },

            capitalizeFirst(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },

            async saveEmployee() {
                this.errors = {};
                try {
                    const url = this.editingEmployee 
                        ? `/employees/${this.currentEmployee.id}`
                        : '/employees';
                    
                    const formData = {
                        ...this.currentEmployee,
                        _method: this.editingEmployee ? 'PUT' : 'POST'
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
                        throw new Error('Failed to save employee');
                    }

                    const savedEmployee = await response.json();

                    if (this.editingEmployee) {
                        const index = this.employees.findIndex(p => p.id === savedEmployee.id);
                        if (index !== -1) this.employees[index] = savedEmployee;
                    } else {
                        this.employees.unshift(savedEmployee);
                    }

                    this.$dispatch('close-modal', 'employee-modal');
                    this.resetForm();
                } catch (error) {
                    console.error('Error saving employee:', error);
                    alert('Failed to save employee. Please try again.');
                }
            },

            async deleteEmployee(id) {
                if (!confirm('Are you sure you want to delete this employee?')) return;

                try {
                    const response = await fetch(`/employees/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error('Failed to delete employee');

                    this.employees = this.employees.filter(p => p.id !== id);
                } catch (error) {
                    console.error('Error deleting employee:', error);
                    alert('Failed to delete employee. Please try again.');
                }
            },

            editEmployee(employee) {
                this.editingEmployee = true;
                this.currentEmployee = {...employee};
                this.$dispatch('open-modal', 'employee-modal');
            },

            resetForm() {
                this.editingEmployee = false;
                this.errors = {};
                this.currentEmployee = {
                    id: null,
                    name: '',
                    type: 'engineer',
                    is_active: true
                };
            }
        }));
    });
</script> 