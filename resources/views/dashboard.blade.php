<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Projects -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-[#ac7909] bg-opacity-20">
                                <i class="fas fa-project-diagram text-[#ac7909] text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Projects</p>
                                <h3 class="text-xl font-semibold text-[#ac7909]">{{ $totalProjects }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Projects -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Active Projects</p>
                                <h3 class="text-xl font-semibold text-green-600 dark:text-green-400">{{ $activeProjects }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Employees -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Employees</p>
                                <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400">{{ $totalEmployees }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedules -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                <i class="fas fa-calendar-day text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Today's Schedules</p>
                                <h3 class="text-xl font-semibold text-purple-600 dark:text-purple-400">{{ $todaySchedules }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Recent Projects -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Projects</h3>
                        <div class="space-y-4">
                            @foreach($recentProjects as $project)
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project->location }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $project->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Monthly Progress Values -->
                <!-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" x-data="progressWidget">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-line mr-2 text-emerald-500"></i>
                                Monthly Progress Values
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button @click="changeMonth('prev')" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formatMonth(selectedMonth)"></span>
                                <button @click="changeMonth('next')" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-if="projects.length > 0">
                                <template x-for="project in projects" :key="project.id">
                                    <div class="border-l-4 border-emerald-500 pl-4 py-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="project.name"></h4>
                                            <span class="px-2 py-1 text-xs rounded-full"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': project.status === 'pending',
                                                    'bg-blue-100 text-blue-800': project.status === 'in_progress',
                                                    'bg-green-100 text-green-800': project.status === 'completed',
                                                    'bg-red-100 text-red-800': project.status === 'cancelled'
                                                }"
                                                x-text="formatStatus(project.status)">
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Current Progress</div>
                                                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400" x-text="formatCurrency(project.progress_value)"></div>
                                            </div>
                                            <template x-if="project.has_previous_progress">
                                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Previous Month</div>
                                                    <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400" x-text="formatCurrency(project.previous_progress_value)"></div>
                                                </div>
                                            </template>
                                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Value</div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formatCurrency(project.total_value)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template x-if="projects.length === 0">
                                <div class="text-center py-4">
                                    <p class="text-gray-500 dark:text-gray-400">No projects with progress in this month</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div> -->

                <!-- Today's Schedule Summary -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Today's Schedule Summary</h3>
                        <div class="space-y-4">
                            @foreach($todayScheduleDetails as $schedule)
                            <a href="{{ route('daily-schedules.index', ['date' => now()->format('Y-m-d')]) }}" class="block border-l-4 border-[#ac7909] pl-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $schedule->project->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Team Leaders: {{ $schedule->supervisors_count }},
                                    Labors: {{ $schedule->technicians_count }},
                                    Engineers: {{ $schedule->engineers_count }}
                                </p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('progressWidget', () => ({
                selectedMonth: '{{ $currentMonth }}',
                
                async changeMonth(direction) {
                    try {
                        const [year, month] = this.selectedMonth.split('-').map(Number);
                        let newYear = year;
                        let newMonth = month;
                        
                        if (direction === 'prev') {
                            newMonth--;
                            if (newMonth < 1) {
                                newMonth = 12;
                                newYear--;
                            }
                        } else {
                            newMonth++;
                            if (newMonth > 12) {
                                newMonth = 1;
                                newYear++;
                            }
                        }
                        
                        const formattedMonth = newMonth.toString().padStart(2, '0');
                        this.selectedMonth = `${newYear}-${formattedMonth}`;
                        
                        console.log('Fetching data for month:', this.selectedMonth);
                        
                        const response = await fetch(`/dashboard/progress?month=${this.selectedMonth}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        console.log('API Response:', data);
                        
                        if (data && Array.isArray(data.projects)) {
                            this.projects = data.projects;
                            console.log('Updated projects:', this.projects);
                        } else {
                            console.error('Invalid data format received:', data);
                        }
                    } catch (error) {
                        console.error('Error changing month:', error);
                    }
                },
                
                formatMonth(yearMonth) {
                    const [year, month] = yearMonth.split('-');
                    return new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
                },
                
                formatCurrency(value) {
                    return value ? `KD ${parseFloat(value).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}` : '-';
                },
                
                formatStatus(status) {
                    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                }
            }))
        })
    </script>
</x-app-layout>
