<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /gearguard/auth/login.php");
exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GearGuard Pro - Advanced Maintenance Management</title>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />

  <!-- Flatpickr -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
  />

  <!-- YOUR MAIN CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

  <body>
    <div class="app-container">
      <!-- Sidebar -->
      <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
          <div class="logo">
            <i class="fas fa-robot"></i>
            <h1>GearGuard Pro</h1>
          </div>
          <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
          </button>
        </div>

        <nav class="sidebar-nav">
          <ul>
            <li class="nav-item active">
              <a href="#dashboard" onclick="navigateTo('dashboard')">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#equipment" onclick="navigateTo('equipment')">
                <i class="fas fa-toolbox"></i>
                <span>Equipment</span>
                <span class="badge badge-new">AI</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#teams" onclick="navigateTo('teams')">
                <i class="fas fa-users"></i>
                <span>Teams</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#requests" onclick="navigateTo('requests')">
                <i class="fas fa-clipboard-list"></i>
                <span>Requests</span>
                <span class="badge badge-new">8</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#calendar" onclick="navigateTo('calendar')">
                <i class="fas fa-calendar-alt"></i>
                <span>Calendar</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#analytics" onclick="navigateTo('analytics')">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#predictive" onclick="navigateTo('predictive')">
                <i class="fas fa-brain"></i>
                <span>Predictive</span>
                <span class="badge badge-new">AI</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#reports" onclick="navigateTo('reports')">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#settings" onclick="navigateTo('settings')">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="sidebar-footer">
          <div class="quick-actions">
            <h3>Quick Actions</h3>
            <p>Generate AI-powered maintenance insights</p>
            <button class="btn btn-primary btn-block" id="aiInsightsBtn">
              <i class="fas fa-robot"></i> AI Insights
            </button>
          </div>
          <div class="theme-toggle" style="margin-top: 1rem">
            <input type="checkbox" id="themeToggle" />
            <label class="theme-slider" for="themeToggle"></label>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <!-- Header -->
        <header class="main-header glass">
          <div class="header-search">
            <i class="fas fa-search"></i>
            <input
              type="text"
              id="globalSearch"
              placeholder="Search equipment, requests, or teams..."
            />
          </div>

          <div class="header-stats">
            <div class="stat-item">
              <span>Equipment Health</span>
              <strong id="equipmentHealth">94%</strong>
            </div>
            <div class="stat-item">
              <span>MTBF</span>
              <strong id="mtbfValue">72.5h</strong>
            </div>
            <div class="stat-item">
              <span>Active Alerts</span>
              <strong id="activeAlerts">3</strong>
            </div>
          </div>

          <div class="header-actions">
            <button
              class="icon-btn notification-btn tooltip"
              title="Notifications"
              onclick="showNotifications()"
            >
              <i class="fas fa-bell"></i>
              <span class="badge">3</span>
            </button>
            <button
              class="icon-btn help-btn tooltip"
              title="Help & Support"
              onclick="showHelp()"
            >
              <i class="fas fa-question-circle"></i>
            </button>
            <button
              class="icon-btn fullscreen-btn tooltip"
              title="Toggle Fullscreen"
              onclick="toggleFullscreen()"
            >
              <i class="fas fa-expand"></i>
            </button>
            <div
              class="user-profile"
              id="userProfile"
              onclick="toggleUserMenu()"
            >

              <div class="avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="user-info">
                <span class="user-name">
  <?php echo $_SESSION['user_name']; ?>
</span>

<span class="user-role">
  <?php echo ucfirst($_SESSION['user_role']); ?>
</span>

              </div>
              <i class="fas fa-chevron-down"></i>
            </div>
              <a
                href="auth/logout.php"
                style="
                    font-size: 12px;
                    color: #2563eb;
                    text-decoration: none;
                    margin-left: 10px;
                    font-weight: 600;
                "
            >
                  Logout
            </a>
          </div>
        </header>

        <!-- Content Area -->
        <div class="content-area" id="contentArea">
          <!-- Dashboard Content -->
          <section id="dashboard" class="page-content active">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Intelligent Dashboard</h2>
                <p>
                  AI-powered insights and real-time monitoring of your
                  maintenance operations
                </p>
              </div>
              <div class="page-header-right">
                <button
                  class="btn btn-outline"
                  id="exportDashboard"
                  onclick="exportDashboardData()"
                >
                  <i class="fas fa-download"></i> Export
                </button>
                <button
                  class="btn btn-primary"
                  id="refreshDashboard"
                  onclick="refreshDashboard()"
                >
                  <i class="fas fa-sync-alt"></i> Refresh
                </button>
              </div>
            </div>

            <!-- AI Recommendations -->
            <div class="ai-recommendations">
              <div class="ai-header">
                <div class="ai-icon">
                  <i class="fas fa-robot"></i>
                </div>
                <h3>AI Maintenance Recommendations</h3>
                <span class="badge badge-new pulse">Live</span>
              </div>
              <div id="aiRecommendations">
                <!-- AI recommendations will be loaded here -->
              </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
              <div class="stat-card">
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #3b82f6, #0ea5e9)"
                >
                  <i class="fas fa-toolbox"></i>
                </div>
                <div class="stat-info">
                  <h3>Active Equipment</h3>
                  <span class="stat-value">24/30</span>
                  <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12%
                  </span>
                </div>
              </div>

              <div class="stat-card">
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #10b981, #059669)"
                >
                  <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                  <h3>Uptime</h3>
                  <span class="stat-value">98.7%</span>
                  <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 0.5%
                  </span>
                </div>
              </div>

              <div class="stat-card">
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #8b5cf6, #7c3aed)"
                >
                  <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                  <h3>Team Efficiency</h3>
                  <span class="stat-value">92%</span>
                  <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 8%
                  </span>
                </div>
              </div>

              <div class="stat-card">
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #f59e0b, #d97706)"
                >
                  <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                  <h3>Avg. Repair Time</h3>
                  <span class="stat-value">4.2h</span>
                  <span class="stat-change negative">
                    <i class="fas fa-arrow-down"></i> 1.3%
                  </span>
                </div>
              </div>
            </div>

            <!-- Predictive Grid -->
            <div class="predictive-grid">
              <div class="predictive-card">
                <div class="predictive-header">
                  <div class="predictive-icon">
                    <i class="fas fa-brain"></i>
                  </div>
                  <h3>Predictive Health Score</h3>
                </div>
                <div class="predictive-stats">
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">94</span>
                    <span class="predictive-stat-label">Current</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">88</span>
                    <span class="predictive-stat-label">30-day Avg</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">+6</span>
                    <span class="predictive-stat-label">Improvement</span>
                  </div>
                </div>
                <div class="predictive-progress">
                  <div class="progress-bar">
                    <div
                      class="progress-fill"
                      style="
                        width: 94%;
                        background: linear-gradient(90deg, #10b981, #059669);
                      "
                    ></div>
                  </div>
                  <div class="progress-labels">
                    <span>Low Risk</span>
                    <span>High Risk</span>
                  </div>
                </div>
              </div>

              <div class="predictive-card">
                <div class="predictive-header">
                  <div
                    class="predictive-icon"
                    style="
                      background: linear-gradient(135deg, #f59e0b, #d97706);
                    "
                  >
                    <i class="fas fa-exclamation-triangle"></i>
                  </div>
                  <h3>Risk Assessment</h3>
                </div>
                <div class="predictive-stats">
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">3</span>
                    <span class="predictive-stat-label">High Risk</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">7</span>
                    <span class="predictive-stat-label">Medium</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">20</span>
                    <span class="predictive-stat-label">Low</span>
                  </div>
                </div>
                <div class="predictive-progress">
                  <div class="progress-bar">
                    <div
                      class="progress-fill"
                      style="
                        width: 10%;
                        background: linear-gradient(90deg, #ef4444, #dc2626);
                      "
                    ></div>
                    <div
                      class="progress-fill"
                      style="
                        width: 23%;
                        background: linear-gradient(90deg, #f59e0b, #d97706);
                        margin-left: -10%;
                      "
                    ></div>
                    <div
                      class="progress-fill"
                      style="
                        width: 67%;
                        background: linear-gradient(90deg, #10b981, #059669);
                        margin-left: -23%;
                      "
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="page-header" style="margin-top: 2rem">
              <h3>Quick Actions</h3>
            </div>
            <div class="stats-grid">
              <div
                class="stat-card"
                onclick="showAddEquipmentModal()"
                style="cursor: pointer"
              >
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #3b82f6, #0ea5e9)"
                >
                  <i class="fas fa-plus"></i>
                </div>
                <div class="stat-info">
                  <h3>Add New Equipment</h3>
                  <span class="stat-value">Quick Add</span>
                </div>
              </div>

              <div
                class="stat-card"
                onclick="showScheduleModal()"
                style="cursor: pointer"
              >
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #10b981, #059669)"
                >
                  <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-info">
                  <h3>Schedule Maintenance</h3>
                  <span class="stat-value">Plan Ahead</span>
                </div>
              </div>

              <div
                class="stat-card"
                onclick="navigateTo('analytics')"
                style="cursor: pointer"
              >
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #8b5cf6, #7c3aed)"
                >
                  <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-info">
                  <h3>View Reports</h3>
                  <span class="stat-value">Insights</span>
                </div>
              </div>

              <div
                class="stat-card"
                onclick="runPredictiveAnalysis()"
                style="cursor: pointer"
              >
                <div
                  class="stat-icon"
                  style="background: linear-gradient(135deg, #f59e0b, #d97706)"
                >
                  <i class="fas fa-bolt"></i>
                </div>
                <div class="stat-info">
                  <h3>Run AI Analysis</h3>
                  <span class="stat-value">Predictive</span>
                </div>
              </div>
            </div>
          </section>

          <!-- Equipment Content -->
          <section id="equipment" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Equipment Management</h2>
                <p>Manage all your equipment assets with AI-powered insights</p>
              </div>
              <div class="page-header-right">
                <button
                  class="btn btn-primary"
                  onclick="showAddEquipmentModal()"
                >
                  <i class="fas fa-plus"></i> Add Equipment
                </button>
              </div>
            </div>

            <!-- Equipment Grid -->
            <div class="equipment-grid" id="equipmentGrid">
              <!-- Equipment cards will be loaded here -->
            </div>
          </section>

          <!-- Teams Content -->
          <section id="teams" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Maintenance Teams</h2>
                <p>Manage teams and assign maintenance tasks efficiently</p>
              </div>
              <div class="page-header-right">
                <button class="btn btn-primary" onclick="showAddTeamModal()">
                  <i class="fas fa-plus"></i> Add Team
                </button>
              </div>
            </div>

            <div class="equipment-grid" id="teamsGrid">
              <!-- Team cards will be loaded here -->
            </div>
          </section>

          <!-- Requests Content -->
          <section id="requests" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Maintenance Requests</h2>
                <p>Track and manage all maintenance requests</p>
              </div>
              <div class="page-header-right">
                <button class="btn btn-primary" onclick="showAddRequestModal()">
                  <i class="fas fa-plus"></i> New Request
                </button>
              </div>
            </div>

            <div class="kanban-board">
              <div class="kanban-column">
                <div class="kanban-header">
                  <h3><i class="fas fa-clock"></i> Pending</h3>
                  <span class="kanban-count">3</span>
                </div>
                <div class="kanban-cards" id="pendingRequests">
                  <!-- Pending requests will be loaded here -->
                </div>
              </div>

              <div class="kanban-column">
                <div class="kanban-header">
                  <h3><i class="fas fa-play-circle"></i> In Progress</h3>
                  <span class="kanban-count">2</span>
                </div>
                <div class="kanban-cards" id="inProgressRequests">
                  <!-- In progress requests will be loaded here -->
                </div>
              </div>

              <div class="kanban-column">
                <div class="kanban-header">
                  <h3><i class="fas fa-check-circle"></i> Completed</h3>
                  <span class="kanban-count">5</span>
                </div>
                <div class="kanban-cards" id="completedRequests">
                  <!-- Completed requests will be loaded here -->
                </div>
              </div>
            </div>
          </section>

          <!-- Calendar Content -->
          <section id="calendar" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Maintenance Calendar</h2>
                <p>
                  Schedule and manage all maintenance activities with drag &
                  drop
                </p>
              </div>
              <div class="page-header-right">
                <div class="calendar-nav">
                  <button class="calendar-nav-btn" id="prevMonth">
                    <i class="fas fa-chevron-left"></i>
                  </button>
                  <div class="calendar-view-toggle">
                    <button
                      class="view-btn active"
                      data-view="month"
                      onclick="changeCalendarView('month')"
                    >
                      Month
                    </button>
                    <button
                      class="view-btn"
                      data-view="week"
                      onclick="changeCalendarView('week')"
                    >
                      Week
                    </button>
                    <button
                      class="view-btn"
                      data-view="day"
                      onclick="changeCalendarView('day')"
                    >
                      Day
                    </button>
                  </div>
                  <button class="calendar-nav-btn" id="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
                <button class="btn btn-primary" onclick="showScheduleModal()">
                  <i class="fas fa-plus"></i> Schedule
                </button>
              </div>
            </div>

            <!-- Calendar Container -->
            <div class="calendar-container">
              <div class="calendar-header">
                <h3 id="currentMonth">November 2023</h3>
                <div class="calendar-legend">
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background: #10b981"
                    ></span>
                    <span>Preventive</span>
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background: #3b82f6"
                    ></span>
                    <span>Corrective</span>
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background: #ef4444"
                    ></span>
                    <span>Urgent</span>
                  </div>
                </div>
              </div>

              <!-- Month View -->
              <div class="calendar-grid-view" id="monthView">
                <div class="calendar-weekdays" id="weekdays">
                  <!-- Weekdays will be generated here -->
                </div>
                <div class="calendar-days" id="calendarDays">
                  <!-- Calendar days will be generated here -->
                </div>
              </div>

              <!-- Week View -->
              <div class="calendar-list-view" id="weekView">
                <div class="time-slots" id="weekSlots">
                  <!-- Week view will be generated here -->
                </div>
              </div>

              <!-- Day View -->
              <div class="calendar-list-view" id="dayView">
                <div class="time-slots" id="daySlots">
                  <!-- Day view will be generated here -->
                </div>
              </div>
            </div>

            <!-- Upcoming Events -->
            <div class="page-header" style="margin-top: 2rem">
              <h3>Upcoming Maintenance</h3>
              <button class="btn btn-outline" onclick="showAllEvents()">
                View All
              </button>
            </div>
            <div class="kanban-board" id="upcomingEvents">
              <!-- Upcoming events will be loaded here -->
            </div>
          </section>

          <!-- Analytics Content -->
          <section id="analytics" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Analytics Dashboard</h2>
                <p>Advanced analytics and performance metrics</p>
              </div>
              <div class="page-header-right">
                <select
                  class="btn btn-secondary"
                  id="analyticsPeriod"
                  onchange="updateAnalytics()"
                >
                  <option value="7d">Last 7 Days</option>
                  <option value="30d" selected>Last 30 Days</option>
                  <option value="90d">Last 90 Days</option>
                  <option value="1y">Last Year</option>
                </select>
              </div>
            </div>

            <!-- Analytics Charts -->
            <div class="analytics-grid">
              <div class="chart-container">
                <div class="chart-header">
                  <h3>Maintenance Trends</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('trendsChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="trendsChart"></canvas>
                </div>
              </div>

              <div class="chart-container">
                <div class="chart-header">
                  <h3>Equipment Health Distribution</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('healthChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="healthChart"></canvas>
                </div>
              </div>

              <div class="chart-container">
                <div class="chart-header">
                  <h3>Team Performance</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('performanceChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="performanceChart"></canvas>
                </div>
              </div>

              <div class="chart-container">
                <div class="chart-header">
                  <h3>Cost Analysis</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('costChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="costChart"></canvas>
                </div>
              </div>
            </div>
          </section>

          <!-- Predictive Content -->
          <section id="predictive" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Predictive Maintenance</h2>
                <p>AI-powered predictions and failure forecasting</p>
              </div>
              <div class="page-header-right">
                <button
                  class="btn btn-primary"
                  onclick="runPredictiveAnalysis()"
                >
                  <i class="fas fa-play"></i> Run Analysis
                </button>
              </div>
            </div>

            <!-- Predictive Models -->
            <div class="predictive-grid">
              <div class="predictive-card">
                <div class="predictive-header">
                  <div
                    class="predictive-icon"
                    style="
                      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
                    "
                  >
                    <i class="fas fa-chart-line"></i>
                  </div>
                  <h3>Failure Prediction</h3>
                </div>
                <div class="predictive-stats">
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">87%</span>
                    <span class="predictive-stat-label">Accuracy</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">5</span>
                    <span class="predictive-stat-label">Predicted</span>
                  </div>
                </div>
                <div class="predictive-progress">
                  <div class="progress-bar">
                    <div
                      class="progress-fill"
                      style="
                        width: 87%;
                        background: linear-gradient(90deg, #8b5cf6, #7c3aed);
                      "
                    ></div>
                  </div>
                </div>
              </div>

              <div class="predictive-card">
                <div class="predictive-header">
                  <div
                    class="predictive-icon"
                    style="
                      background: linear-gradient(135deg, #10b981, #059669);
                    "
                  >
                    <i class="fas fa-cogs"></i>
                  </div>
                  <h3>Remaining Useful Life</h3>
                </div>
                <div class="predictive-stats">
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">142</span>
                    <span class="predictive-stat-label">Avg. Days</span>
                  </div>
                  <div class="predictive-stat">
                    <span class="predictive-stat-value">3</span>
                    <span class="predictive-stat-label">Critical</span>
                  </div>
                </div>
                <div class="predictive-progress">
                  <div class="progress-bar">
                    <div
                      class="progress-fill"
                      style="
                        width: 65%;
                        background: linear-gradient(90deg, #10b981, #059669);
                      "
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Predictive Insights -->
            <div class="ai-recommendations" style="margin-top: 2rem">
              <div class="ai-header">
                <div class="ai-icon">
                  <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Predictive Insights</h3>
              </div>
              <div id="predictiveInsights">
                <!-- Predictive insights will be loaded here -->
              </div>
            </div>
          </section>

          <!-- Reports Content -->
          <section id="reports" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Reports & Analytics</h2>
                <p>Generate detailed reports and insights</p>
              </div>
              <div class="page-header-right">
                <button class="btn btn-primary" onclick="generateReport()">
                  <i class="fas fa-file-pdf"></i> Generate Report
                </button>
              </div>
            </div>

            <div class="analytics-grid">
              <div class="chart-container">
                <div class="chart-header">
                  <h3>Monthly Performance</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('monthlyChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="monthlyChart"></canvas>
                </div>
              </div>

              <div class="chart-container">
                <div class="chart-header">
                  <h3>Cost Breakdown</h3>
                  <button
                    class="btn btn-sm btn-outline"
                    onclick="downloadChart('costBreakdownChart')"
                  >
                    <i class="fas fa-download"></i>
                  </button>
                </div>
                <div class="chart-wrapper">
                  <canvas id="costBreakdownChart"></canvas>
                </div>
              </div>
            </div>
          </section>

          <!-- Settings Content -->
          <section id="settings" class="page-content">
            <div class="page-header">
              <div class="page-header-left">
                <h2>Settings</h2>
                <p>Configure system settings and preferences</p>
              </div>
            </div>

            <div class="predictive-grid">
              <div class="predictive-card">
                <div class="predictive-header">
                  <div
                    class="predictive-icon"
                    style="
                      background: linear-gradient(135deg, #3b82f6, #0ea5e9);
                    "
                  >
                    <i class="fas fa-user-cog"></i>
                  </div>
                  <h3>User Settings</h3>
                </div>
                <div class="form-group">
                  <label>Email Notifications</label>
                  <select class="form-control">
                    <option>Enabled</option>
                    <option>Disabled</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Default Dashboard</label>
                  <select class="form-control">
                    <option>Intelligent Dashboard</option>
                    <option>Analytics Dashboard</option>
                    <option>Predictive Dashboard</option>
                  </select>
                </div>
                <button
                  class="btn btn-primary btn-block"
                  onclick="saveUserSettings()"
                >
                  Save Settings
                </button>
              </div>

              <div class="predictive-card">
                <div class="predictive-header">
                  <div
                    class="predictive-icon"
                    style="
                      background: linear-gradient(135deg, #10b981, #059669);
                    "
                  >
                    <i class="fas fa-bell"></i>
                  </div>
                  <h3>Notification Settings</h3>
                </div>
                <div class="form-group">
                  <label>
                    <input type="checkbox" checked /> Maintenance Alerts
                  </label>
                </div>
                <div class="form-group">
                  <label>
                    <input type="checkbox" checked /> AI Recommendations
                  </label>
                </div>
                <div class="form-group">
                  <label> <input type="checkbox" /> Predictive Warnings </label>
                </div>
                <button
                  class="btn btn-primary btn-block"
                  onclick="saveNotificationSettings()"
                >
                  Save Preferences
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>

      <!-- Modals -->
      <div class="modal" id="addEquipmentModal">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Add New Equipment</h3>
            <button
              class="modal-close"
              onclick="closeModal('addEquipmentModal')"
            >
              &times;
            </button>
          </div>
          <div class="modal-body">
            <form id="equipmentForm" onsubmit="addEquipment(event)">
              <div class="form-group">
                <label>Equipment Name</label>
                <input type="text" id="equipmentName" required />
              </div>
              <div class="form-group">
                <label>Serial Number</label>
                <input type="text" id="serialNumber" required />
              </div>
              <div class="form-group">
                <label>Category</label>
                <select id="equipmentCategory" required>
                  <option value="">Select Category</option>
                  <option value="CNC Machine">CNC Machine</option>
                  <option value="Laptop">Laptop</option>
                  <option value="Vehicle">Vehicle</option>
                  <option value="Printer">Printer</option>
                  <option value="Server">Server</option>
                </select>
              </div>
              <div class="form-group">
                <label>Department</label>
                <select id="equipmentDepartment" required>
                  <option value="">Select Department</option>
                  <option value="Production">Production</option>
                  <option value="IT">IT</option>
                  <option value="Logistics">Logistics</option>
                  <option value="Maintenance">Maintenance</option>
                </select>
              </div>
              <div class="form-actions">
                <button
                  type="button"
                  class="btn btn-secondary"
                  onclick="closeModal('addEquipmentModal')"
                >
                  Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                  Add Equipment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal" id="addRequestModal">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Create Maintenance Request</h3>
            <button class="modal-close" onclick="closeModal('addRequestModal')">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <form id="requestForm" onsubmit="addRequest(event)">
              <div class="form-group">
                <label>Request Type</label>
                <select id="requestType" required>
                  <option value="">Select Type</option>
                  <option value="Preventive">Preventive Maintenance</option>
                  <option value="Corrective">Corrective Maintenance</option>
                  <option value="Emergency">Emergency Repair</option>
                </select>
              </div>
              <div class="form-group">
                <label>Equipment</label>
                <select id="requestEquipment" required>
                  <option value="">Select Equipment</option>
                  <!-- Equipment options will be populated -->
                </select>
              </div>
              <div class="form-group">
                <label>Priority</label>
                <select id="requestPriority" required>
                  <option value="Low">Low</option>
                  <option value="Medium" selected>Medium</option>
                  <option value="High">High</option>
                  <option value="Urgent">Urgent</option>
                </select>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea id="requestDescription" rows="3" required></textarea>
              </div>
              <div class="form-actions">
                <button
                  type="button"
                  class="btn btn-secondary"
                  onclick="closeModal('addRequestModal')"
                >
                  Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                  Create Request
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal" id="scheduleModal">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Schedule Maintenance</h3>
            <button class="modal-close" onclick="closeModal('scheduleModal')">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <form id="scheduleForm" onsubmit="scheduleMaintenance(event)">
              <div class="form-group">
                <label>Equipment</label>
                <select id="scheduleEquipment" required>
                  <option value="">Select Equipment</option>
                  <!-- Equipment options will be populated -->
                </select>
              </div>
              <div class="form-group">
                <label>Maintenance Type</label>
                <select id="maintenanceType" required>
                  <option value="Preventive">Preventive Maintenance</option>
                  <option value="Inspection">Inspection</option>
                  <option value="Calibration">Calibration</option>
                </select>
              </div>
              <div class="form-group">
                <label>Date</label>
                <input type="date" id="scheduleDate" required />
              </div>
              <div class="form-group">
                <label>Time</label>
                <input type="time" id="scheduleTime" required />
              </div>
              <div class="form-group">
                <label>Team</label>
                <select id="scheduleTeam" required>
                  <option value="">Select Team</option>
                  <option value="Mechanics">Mechanics</option>
                  <option value="IT Support">IT Support</option>
                  <option value="Electronics">Electronics</option>
                </select>
              </div>
              <div class="form-actions">
                <button
                  type="button"
                  class="btn btn-secondary"
                  onclick="closeModal('scheduleModal')"
                >
                  Cancel
                </button>
                <button type="submit" class="btn btn-primary">Schedule</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Toast Container -->
      <div class="toast-container" id="toastContainer"></div>

      <!-- Context Menu -->
      <div class="context-menu" id="contextMenu">
        <div class="context-menu-item" onclick="editItem()">
          <i class="fas fa-edit"></i> Edit
        </div>
        <div class="context-menu-item" onclick="deleteItem()">
          <i class="fas fa-trash"></i> Delete
        </div>
        <div class="context-menu-item" onclick="scheduleItem()">
          <i class="fas fa-calendar-plus"></i> Schedule
        </div>
        <div class="context-menu-item" onclick="assignItem()">
          <i class="fas fa-user-plus"></i> Assign
        </div>
      </div>
    </div>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Flatpickr -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- YOUR MAIN JS -->
        <script src="assets/js/app.js"></script>
  </body>
</html>
