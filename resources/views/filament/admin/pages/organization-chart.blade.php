<x-filament-panels::page>
    <div x-data="orgChartData()" class="w-full bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4" style="min-height: 700px; height: 75vh;">
        <div id="chart-container" class="w-full h-full" style="min-height: 650px;"></div>
    </div>

    <!-- d3.js -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    
    <!-- d3-flextree (must be loaded before d3-org-chart) -->
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>

    <!-- d3-org-chart -->
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.1.0"></script>

    <script>
        (function() {
            function registerOrgChart() {
                if (typeof Alpine === 'undefined') {
                    setTimeout(registerOrgChart, 50);
                    return;
                }

                Alpine.data('orgChartData', () => ({
                    data: @json($this->getEmployeesData()),
                    chart: null,
                    init() {
                        this.initChart();
                    },
                    initChart() {
                        if (typeof d3 === 'undefined' || typeof d3.flextree === 'undefined' || typeof d3.OrgChart === 'undefined') {
                            setTimeout(() => this.initChart(), 150);
                            return;
                        }

                        const container = document.getElementById('chart-container');
                        if (!container) return;
                        container.innerHTML = '';

                        try {
                            this.chart = new d3.OrgChart()
                                .container('#chart-container')
                                .data(this.data)
                                .nodeWidth(d => 250)
                                .initialZoom(0.75)
                                .nodeHeight(d => 140)
                                .childrenMargin(d => 50)
                                .compactMarginBetween(d => 35)
                                .compactMarginPair(d => 30)
                                .nodeContent(function (d, i, arr, state) {
                                    const isDark = document.documentElement.classList.contains('dark');
                                    const bgColor = isDark ? '#1e293b' : '#FFFFFF';
                                    const borderColor = isDark ? '#334155' : '#E5E7EB';
                                    const titleColor = isDark ? '#F8FAFC' : '#111827';
                                    const subColor = isDark ? '#94A3B8' : '#6B7280';
                                    const imageDiffVert = 25 + 2;
                                    return `
                                        <div style='width:${d.width}px;height:${d.height}px;padding-top:${imageDiffVert - 2}px;padding-left:1px;padding-right:1px'>
                                            <div style="font-family: 'Tajawal', 'Cairo', sans-serif; background-color:${bgColor}; border: 1px solid ${borderColor}; border-radius: 10px; width:${d.width - 2}px; height:${d.height - imageDiffVert}px; box-shadow: 0 4px 12px rgba(0, 0, 0, ${isDark ? '0.35' : '0.08'});">
                                                
                                                <div style="display:flex;justify-content:center;margin-top:-${imageDiffVert}px">
                                                    <img src="${d.data.imageUrl}" style="border-radius:50%; width: 60px; height: 60px; object-fit: cover; border: 3px solid #0d9488;" />
                                                </div>
                                                
                                                <div style="text-align:center; margin-top:10px;">
                                                    <div style="font-size:15px; font-weight:bold; color:${titleColor};">${d.data.name}</div>
                                                    <div style="font-size:13px; color:${subColor}; margin-top:4px;">${d.data.positionName}</div>
                                                    ${d.data.department ? `<div style="font-size:12px; color:#0d9488; margin-top:4px; font-weight: 700;">${d.data.department}</div>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                })
                                .render();
                                
                            this.chart.expandAll();
                        } catch (e) {
                            console.error('Error rendering Organization Chart:', e);
                        }
                    }
                }));
            }

            if (window.Alpine) {
                registerOrgChart();
            } else {
                document.addEventListener('alpine:init', registerOrgChart);
            }
        })();
    </script>
</x-filament-panels::page>
