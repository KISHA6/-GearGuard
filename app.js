      // ===== GLOBAL DATA =====
      let equipmentData = [
        {
          id: 1,
          name: "CNC Machine 01",
          serialNumber: "CNC-2023-001",
          category: "CNC Machine",
          department: "Production",
          status: "working",
          health: 94,
          lastMaintenance: "2023-11-15",
          nextMaintenance: "2023-12-15",
        },
        {
          id: 2,
          name: "Laptop - Design Team",
          serialNumber: "LT-2023-045",
          category: "Laptop",
          department: "IT",
          status: "working",
          health: 88,
          lastMaintenance: "2023-11-10",
          nextMaintenance: "2024-01-10",
        },
        {
          id: 3,
          name: "Delivery Van 03",
          serialNumber: "VAN-2022-003",
          category: "Vehicle",
          department: "Logistics",
          status: "maintenance",
          health: 65,
          lastMaintenance: "2023-11-05",
          nextMaintenance: "2023-11-25",
        },
        {
          id: 4,
          name: "Color Printer 02",
          serialNumber: "PRN-2023-018",
          category: "Printer",
          department: "IT",
          status: "working",
          health: 92,
          lastMaintenance: "2023-10-20",
          nextMaintenance: "2023-12-20",
        },
      ];

      let maintenanceRequests = [
        {
          id: 1,
          equipmentId: 1,
          equipmentName: "CNC Machine 01",
          type: "Preventive",
          priority: "High",
          description: "Monthly maintenance check",
          status: "In Progress",
          date: "2023-11-20",
          assignedTo: "John Smith",
        },
        {
          id: 2,
          equipmentId: 3,
          equipmentName: "Delivery Van 03",
          type: "Emergency",
          priority: "Urgent",
          description: "Engine overheating issue",
          status: "Pending",
          date: "2023-11-18",
          assignedTo: "Mike Johnson",
        },
        {
          id: 3,
          equipmentId: 2,
          equipmentName: "Laptop - Design Team",
          type: "Corrective",
          priority: "Medium",
          description: "Screen replacement",
          status: "Completed",
          date: "2023-11-15",
          assignedTo: "Sarah Wilson",
        },
      ];

      let maintenanceEvents = [
        {
          id: 1,
          title: "Monthly Check - CNC 01",
          type: "preventive",
          equipmentId: 1,
          start: "2023-11-20T09:00",
          end: "2023-11-20T11:00",
          team: "Mechanics",
          priority: "medium",
          status: "scheduled",
        },
        {
          id: 2,
          title: "Van Engine Repair",
          type: "urgent",
          equipmentId: 3,
          start: "2023-11-18T14:00",
          end: "2023-11-18T17:00",
          team: "Mechanics",
          priority: "high",
          status: "scheduled",
        },
        {
          id: 3,
          title: "Server Maintenance",
          type: "preventive",
          equipmentId: 5,
          start: "2023-11-22T22:00",
          end: "2023-11-23T02:00",
          team: "IT Support",
          priority: "medium",
          status: "scheduled",
        },
      ];

      let teamsData = [
        {
          id: 1,
          name: "Mechanics Team",
          members: 4,
          activeRequests: 2,
          completedThisMonth: 12,
        },
        {
          id: 2,
          name: "IT Support",
          members: 3,
          activeRequests: 1,
          completedThisMonth: 18,
        },
        {
          id: 3,
          name: "Electronics",
          members: 2,
          activeRequests: 0,
          completedThisMonth: 8,
        },
      ];

      // ===== STATE MANAGEMENT =====
      let currentCalendarDate = new Date();
      let currentCalendarView = "month";
      let currentContextTarget = null;
      let charts = {};

      // ===== UTILITY FUNCTIONS =====
      function formatDate(date) {
        return new Date(date).toLocaleDateString("en-US", {
          year: "numeric",
          month: "short",
          day: "numeric",
        });
      }

      function formatDateTime(dateTime) {
        return new Date(dateTime).toLocaleString("en-US", {
          month: "short",
          day: "numeric",
          hour: "2-digit",
          minute: "2-digit",
        });
      }

      function showToast(message, type = "info") {
        const container = document.getElementById("toastContainer");
        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        toast.innerHTML = `
                  <span>${message}</span>
                  <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
              `;

        container.appendChild(toast);

        setTimeout(() => {
          if (toast.parentElement) {
            toast.remove();
          }
        }, 5000);
      }

      // ===== MODAL FUNCTIONS =====
      function showModal(modalId) {
        document.getElementById(modalId).classList.add("active");
      }

      function closeModal(modalId) {
        document.getElementById(modalId).classList.remove("active");
      }

      function showAddEquipmentModal() {
        showModal("addEquipmentModal");
      }

      function showAddRequestModal() {
        populateEquipmentSelect("requestEquipment");
        showModal("addRequestModal");
      }

      function showScheduleModal() {
        populateEquipmentSelect("scheduleEquipment");
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById("scheduleDate").valueAsDate = tomorrow;
        showModal("scheduleModal");
      }

      function populateEquipmentSelect(selectId) {
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">Select Equipment</option>';
        equipmentData.forEach((equipment) => {
          const option = document.createElement("option");
          option.value = equipment.id;
          option.textContent = `${equipment.name} (${equipment.serialNumber})`;
          select.appendChild(option);
        });
      }

      // ===== NAVIGATION =====
      function navigateTo(page) {
        // Update active nav item
        document.querySelectorAll(".nav-item").forEach((item) => {
          item.classList.remove("active");
        });
        document
          .querySelector(`.nav-item a[href="#${page}"]`)
          .parentElement.classList.add("active");

        // Show target page
        document.querySelectorAll(".page-content").forEach((pageEl) => {
          pageEl.classList.remove("active");
        });
        document.getElementById(page).classList.add("active");

        // Load page-specific data
        loadPageData(page);

        // Close sidebar on mobile
        if (window.innerWidth <= 1024) {
          document.getElementById("sidebar").classList.remove("active");
        }
      }

      function loadPageData(page) {
        switch (page) {
          case "dashboard":
            loadDashboard();
            break;
          case "equipment":
            loadEquipment();
            break;
          case "teams":
            loadTeams();
            break;
          case "requests":
            loadRequests();
            break;
          case "calendar":
            loadCalendar();
            break;
          case "analytics":
            loadAnalytics();
            break;
          case "predictive":
            loadPredictive();
            break;
          case "reports":
            loadReports();
            break;
          case "settings":
            loadSettings();
            break;
        }
      }

      // ===== DASHBOARD =====
      function loadDashboard() {
        loadAIRecommendations();
        updateDashboardStats();
      }

      function loadAIRecommendations() {
        const recommendations = [
          {
            icon: "fas fa-exclamation-triangle",
            title: "High Priority Alert",
            description: "CNC Machine 01 shows abnormal vibration patterns",
            action: "Schedule immediate inspection",
            priority: "high",
          },
          {
            icon: "fas fa-calendar-check",
            title: "Preventive Maintenance Due",
            description: "3 equipment items due for monthly maintenance",
            action: "Schedule maintenance for next week",
            priority: "medium",
          },
          {
            icon: "fas fa-chart-line",
            title: "Performance Improvement",
            description: "Team efficiency increased by 15% this month",
            action: "Review best practices",
            priority: "low",
          },
        ];

        const container = document.getElementById("aiRecommendations");
        container.innerHTML = "";

        recommendations.forEach((rec) => {
          const item = document.createElement("div");
          item.className = "recommendation-item";
          item.innerHTML = `
                      <div class="recommendation-icon">
                          <i class="${rec.icon}"></i>
                      </div>
                      <div class="recommendation-content">
                          <h4>${rec.title}</h4>
                          <p>${rec.description}</p>
                          <div class="recommendation-meta">
                              <span><i class="fas fa-clock"></i> ${
                                rec.action
                              }</span>
                              <span class="badge ${
                                rec.priority === "high"
                                  ? "badge-overdue"
                                  : rec.priority === "medium"
                                  ? "badge-in-progress"
                                  : "badge-completed"
                              }">${rec.priority}</span>
                          </div>
                      </div>
                  `;
          container.appendChild(item);
        });
      }

      function updateDashboardStats() {
        const workingEquipment = equipmentData.filter(
          (e) => e.status === "working"
        ).length;
        const totalEquipment = equipmentData.length;
        const efficiency = Math.round(
          (workingEquipment / totalEquipment) * 100
        );

        document.getElementById(
          "equipmentHealth"
        ).textContent = `${efficiency}%`;

        // Update other stats
        const pendingRequests = maintenanceRequests.filter(
          (r) => r.status === "Pending"
        ).length;
        document.getElementById("activeAlerts").textContent = pendingRequests;
      }

      function refreshDashboard() {
        const btn = document.getElementById("refreshDashboard");
        btn.classList.add("loading");

        // Simulate API call
        setTimeout(() => {
          btn.classList.remove("loading");
          showToast("Dashboard refreshed successfully!", "success");
          loadDashboard();
        }, 1000);
      }

      function exportDashboardData() {
        showToast("Exporting dashboard data...", "info");
        // Simulate export
        setTimeout(() => {
          showToast("Dashboard data exported successfully!", "success");
        }, 1500);
      }

      // ===== EQUIPMENT =====
      function loadEquipment() {
        const container = document.getElementById("equipmentGrid");
        container.innerHTML = "";

        equipmentData.forEach((equipment) => {
          const card = createEquipmentCard(equipment);
          container.appendChild(card);
        });
      }

      function createEquipmentCard(equipment) {
        const card = document.createElement("div");
        card.className = "equipment-card";
        card.setAttribute("data-id", equipment.id);
        card.oncontextmenu = (e) => {
          e.preventDefault();
          showContextMenu(e, equipment);
        };

        card.innerHTML = `
                  <div class="equipment-header">
                      <div class="equipment-title">
                          <h3>${equipment.name}</h3>
                          <span>${equipment.serialNumber}</span>
                      </div>
                      <span class="equipment-status status-${equipment.status}">
                          ${
                            equipment.status === "working"
                              ? "Working"
                              : equipment.status === "maintenance"
                              ? "Under Maintenance"
                              : "Scrap"
                          }
                      </span>
                  </div>

                  <div class="equipment-details">
                      <div class="detail-item">
                          <span class="detail-label">Category</span>
                          <span class="detail-value">${
                            equipment.category
                          }</span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Department</span>
                          <span class="detail-value">${
                            equipment.department
                          }</span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Health Score</span>
                          <span class="detail-value">${equipment.health}%</span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Next Maintenance</span>
                          <span class="detail-value">${formatDate(
                            equipment.nextMaintenance
                          )}</span>
                      </div>
                  </div>

                  <div class="equipment-actions">
                      <button class="btn btn-sm btn-secondary" onclick="viewEquipment(${
                        equipment.id
                      })">
                          <i class="fas fa-eye"></i> View
                      </button>
                      <button class="btn btn-sm btn-primary" onclick="createRequestForEquipment(${
                        equipment.id
                      })">
                          <i class="fas fa-plus"></i> Request
                      </button>
                  </div>
              `;

        return card;
      }

      function addEquipment(event) {
        event.preventDefault();

        const equipment = {
          id: equipmentData.length + 1,
          name: document.getElementById("equipmentName").value,
          serialNumber: document.getElementById("serialNumber").value,
          category: document.getElementById("equipmentCategory").value,
          department: document.getElementById("equipmentDepartment").value,
          status: "working",
          health: 95,
          lastMaintenance: new Date().toISOString().split("T")[0],
          nextMaintenance: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)
            .toISOString()
            .split("T")[0],
        };

        equipmentData.push(equipment);
        closeModal("addEquipmentModal");
        loadEquipment();
        updateDashboardStats();
        showToast("Equipment added successfully!", "success");

        // Reset form
        document.getElementById("equipmentForm").reset();
      }

      function viewEquipment(id) {
        const equipment = equipmentData.find((e) => e.id === id);
        if (!equipment) return;

        showToast(`Viewing ${equipment.name}`, "info");
        // In a real app, this would open a detailed view modal
      }

      function createRequestForEquipment(id) {
        const equipment = equipmentData.find((e) => e.id === id);
        if (!equipment) return;

        showAddRequestModal();
        document.getElementById("requestEquipment").value = id;
      }

      // ===== REQUESTS =====
      function loadRequests() {
        const pendingContainer = document.getElementById("pendingRequests");
        const inProgressContainer =
          document.getElementById("inProgressRequests");
        const completedContainer = document.getElementById("completedRequests");

        pendingContainer.innerHTML = "";
        inProgressContainer.innerHTML = "";
        completedContainer.innerHTML = "";

        maintenanceRequests.forEach((request) => {
          const card = createRequestCard(request);

          switch (request.status) {
            case "Pending":
              pendingContainer.appendChild(card);
              break;
            case "In Progress":
              inProgressContainer.appendChild(card);
              break;
            case "Completed":
              completedContainer.appendChild(card);
              break;
          }
        });
      }

      function createRequestCard(request) {
        const card = document.createElement("div");
        card.className = "kanban-card";
        card.setAttribute("draggable", "true");
        card.setAttribute("data-id", request.id);

        card.ondragstart = (e) => {
          e.dataTransfer.setData("text/plain", request.id);
          card.classList.add("dragging");
        };

        card.ondragend = () => {
          card.classList.remove("dragging");
        };

        const priorityClass = request.priority.toLowerCase();

        card.innerHTML = `
                  <div class="kanban-card-header">
                      <div class="kanban-card-title">${
                        request.equipmentName
                      }</div>
                      <span class="badge badge-${
                        priorityClass === "urgent"
                          ? "overdue"
                          : priorityClass === "high"
                          ? "in-progress"
                          : "completed"
                      }">${request.priority}</span>
                  </div>
                  <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">${
                    request.description
                  }</p>
                  <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--gray-500);">
                      <span>${formatDate(request.date)}</span>
                      <span>${request.assignedTo}</span>
                  </div>
              `;

        return card;
      }

      function addRequest(event) {
        event.preventDefault();

        const equipmentId = parseInt(
          document.getElementById("requestEquipment").value
        );
        const equipment = equipmentData.find((e) => e.id === equipmentId);

        if (!equipment) {
          showToast("Please select valid equipment", "error");
          return;
        }

        const request = {
          id: maintenanceRequests.length + 1,
          equipmentId: equipmentId,
          equipmentName: equipment.name,
          type: document.getElementById("requestType").value,
          priority: document.getElementById("requestPriority").value,
          description: document.getElementById("requestDescription").value,
          status: "Pending",
          date: new Date().toISOString().split("T")[0],
          assignedTo: "Unassigned",
        };

        maintenanceRequests.push(request);
        closeModal("addRequestModal");
        loadRequests();
        updateDashboardStats();
        showToast("Maintenance request created successfully!", "success");

        // Reset form
        document.getElementById("requestForm").reset();
      }

      // ===== CALENDAR =====
      function loadCalendar() {
        renderCalendar();
        loadUpcomingEvents();
        setupCalendarDragDrop();
      }

      function renderCalendar() {
        const monthNames = [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ];

        document.getElementById("currentMonth").textContent = `${
          monthNames[currentCalendarDate.getMonth()]
        } ${currentCalendarDate.getFullYear()}`;

        if (currentCalendarView === "month") {
          renderMonthView();
        } else {
          // Hide other views, show current view
          document.getElementById("monthView").style.display = "none";
          document.getElementById("weekView").classList.remove("active");
          document.getElementById("dayView").classList.remove("active");
          document
            .getElementById(currentCalendarView + "View")
            .classList.add("active");
        }
      }

      function renderMonthView() {
        const calendarDays = document.getElementById("calendarDays");
        const weekdays = document.getElementById("weekdays");

        calendarDays.innerHTML = "";
        weekdays.innerHTML = "";

        // Render weekdays
        const weekdaysArr = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        weekdaysArr.forEach((day) => {
          const dayEl = document.createElement("div");
          dayEl.className = "weekday";
          dayEl.textContent = day;
          weekdays.appendChild(dayEl);
        });

        // Get first day of month and total days
        const firstDay = new Date(
          currentCalendarDate.getFullYear(),
          currentCalendarDate.getMonth(),
          1
        );
        const lastDay = new Date(
          currentCalendarDate.getFullYear(),
          currentCalendarDate.getMonth() + 1,
          0
        );
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();

        // Get previous month's days
        const prevMonthLastDay = new Date(
          currentCalendarDate.getFullYear(),
          currentCalendarDate.getMonth(),
          0
        ).getDate();

        // Create calendar grid
        for (let i = 0; i < 42; i++) {
          const dayEl = document.createElement("div");
          dayEl.className = "calendar-day";

          let dayNumber;
          let isToday = false;
          let isOtherMonth = false;

          if (i < startingDay) {
            // Previous month
            dayNumber = prevMonthLastDay - startingDay + i + 1;
            isOtherMonth = true;
          } else if (i >= startingDay + daysInMonth) {
            // Next month
            dayNumber = i - startingDay - daysInMonth + 1;
            isOtherMonth = true;
          } else {
            // Current month
            dayNumber = i - startingDay + 1;

            // Check if today
            const today = new Date();
            isToday =
              today.getDate() === dayNumber &&
              today.getMonth() === currentCalendarDate.getMonth() &&
              today.getFullYear() === currentCalendarDate.getFullYear();
          }

          if (isToday) {
            dayEl.classList.add("today");
          }
          if (isOtherMonth) {
            dayEl.classList.add("other-month");
          }

          // Add day number
          const dayHeader = document.createElement("div");
          dayHeader.className = "day-header";
          dayHeader.innerHTML = `<span class="day-number">${dayNumber}</span>`;
          dayEl.appendChild(dayHeader);

          // Add events for this day
          const dayEvents = document.createElement("div");
          dayEvents.className = "day-events";

          const eventsForDay = maintenanceEvents.filter((event) => {
            const eventDate = new Date(event.start);
            return (
              eventDate.getDate() === dayNumber &&
              eventDate.getMonth() === currentCalendarDate.getMonth() &&
              eventDate.getFullYear() === currentCalendarDate.getFullYear()
            );
          });

          if (eventsForDay.length > 0) {
            dayEl.classList.add("has-events");
            eventsForDay.slice(0, 2).forEach((event) => {
              const eventEl = document.createElement("div");
              eventEl.className = `day-event event-${event.type}`;
              eventEl.textContent = event.title;
              eventEl.setAttribute("data-event-id", event.id);
              eventEl.setAttribute("draggable", "true");
              dayEvents.appendChild(eventEl);
            });

            if (eventsForDay.length > 2) {
              const moreEl = document.createElement("div");
              moreEl.className = "day-event";
              moreEl.textContent = `+${eventsForDay.length - 2} more`;
              dayEvents.appendChild(moreEl);
            }
          }

          dayEl.appendChild(dayEvents);
          calendarDays.appendChild(dayEl);
        }

        // Show month view
        document.getElementById("monthView").style.display = "block";
        document.getElementById("weekView").classList.remove("active");
        document.getElementById("dayView").classList.remove("active");
      }

      function changeCalendarView(view) {
        currentCalendarView = view;
        document.querySelectorAll(".view-btn").forEach((btn) => {
          btn.classList.remove("active");
        });
        event.target.classList.add("active");
        renderCalendar();
      }

      function prevMonth() {
        currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
        renderCalendar();
      }

      function nextMonth() {
        currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
        renderCalendar();
      }

      function setupCalendarDragDrop() {
        const calendarDays = document.getElementById("calendarDays");

        calendarDays.addEventListener("dragover", function (e) {
          e.preventDefault();
          const dayEl = e.target.closest(".calendar-day");
          if (dayEl && !dayEl.classList.contains("other-month")) {
            dayEl.style.backgroundColor = "var(--primary-50)";
          }
        });

        calendarDays.addEventListener("drop", function (e) {
          e.preventDefault();
          const dayEl = e.target.closest(".calendar-day");
          if (dayEl && !dayEl.classList.contains("other-month")) {
            const eventId = e.dataTransfer.getData("text/plain");
            const dayNumber = parseInt(
              dayEl.querySelector(".day-number").textContent
            );

            // Update event date
            const eventIndex = maintenanceEvents.findIndex(
              (event) => event.id == eventId
            );
            if (eventIndex !== -1) {
              const event = maintenanceEvents[eventIndex];
              const oldDate = new Date(event.start);
              const newDate = new Date(
                currentCalendarDate.getFullYear(),
                currentCalendarDate.getMonth(),
                dayNumber,
                oldDate.getHours(),
                oldDate.getMinutes()
              );

              event.start = newDate.toISOString().slice(0, 16);

              showToast(
                `Event rescheduled to ${newDate.toLocaleDateString()}`,
                "success"
              );
              renderCalendar();
            }

            dayEl.style.backgroundColor = "";
          }
        });
      }

      function loadUpcomingEvents() {
        const container = document.getElementById("upcomingEvents");
        container.innerHTML = "";

        // Add kanban columns for upcoming events
        const columns = [
          { title: "Today", days: 0 },
          { title: "Tomorrow", days: 1 },
          { title: "This Week", days: 7 },
        ];

        columns.forEach((col) => {
          const column = document.createElement("div");
          column.className = "kanban-column";

          const targetDate = new Date();
          targetDate.setDate(targetDate.getDate() + col.days);
          const targetDateStr = targetDate.toISOString().split("T")[0];

          const eventsForDay = maintenanceEvents.filter((event) => {
            const eventDate = new Date(event.start).toISOString().split("T")[0];
            return eventDate === targetDateStr;
          });

          column.innerHTML = `
                      <div class="kanban-header">
                          <h3><i class="fas fa-calendar-day"></i> ${
                            col.title
                          }</h3>
                          <span class="kanban-count">${
                            eventsForDay.length
                          }</span>
                      </div>
                      <div class="kanban-cards">
                          ${eventsForDay
                            .map(
                              (event) => `
                              <div class="kanban-card">
                                  <div class="kanban-card-header">
                                      <div class="kanban-card-title">${
                                        event.title
                                      }</div>
                                  </div>
                                  <p style="font-size: 0.875rem; color: var(--gray-600);">${formatDateTime(
                                    event.start
                                  )}</p>
                                  <div style="font-size: 0.75rem; color: var(--gray-500);">Team: ${
                                    event.team
                                  }</div>
                              </div>
                          `
                            )
                            .join("")}
                      </div>
                  `;

          container.appendChild(column);
        });
      }

      function showAllEvents() {
        navigateTo("calendar");
      }

      function scheduleMaintenance(event) {
        event.preventDefault();

        const equipmentId = parseInt(
          document.getElementById("scheduleEquipment").value
        );
        const equipment = equipmentData.find((e) => e.id === equipmentId);

        if (!equipment) {
          showToast("Please select valid equipment", "error");
          return;
        }

        const scheduleDate = document.getElementById("scheduleDate").value;
        const scheduleTime = document.getElementById("scheduleTime").value;
        const startDateTime = `${scheduleDate}T${scheduleTime}`;

        // Calculate end time (2 hours later by default)
        const endDate = new Date(startDateTime);
        endDate.setHours(endDate.getHours() + 2);
        const endDateTime = endDate.toISOString().slice(0, 16);

        const eventData = {
          id: maintenanceEvents.length + 1,
          title: `${document.getElementById("maintenanceType").value} - ${
            equipment.name
          }`,
          type: document
            .getElementById("maintenanceType")
            .value.toLowerCase()
            .includes("preventive")
            ? "preventive"
            : "corrective",
          equipmentId: equipmentId,
          start: startDateTime,
          end: endDateTime,
          team: document.getElementById("scheduleTeam").value,
          priority: "medium",
          status: "scheduled",
        };

        maintenanceEvents.push(eventData);
        closeModal("scheduleModal");
        loadCalendar();
        showToast("Maintenance scheduled successfully!", "success");

        // Reset form
        document.getElementById("scheduleForm").reset();
      }

      // ===== ANALYTICS =====
      function loadAnalytics() {
        initCharts();
      }

      function initCharts() {
        if (charts.trendsChart) {
          charts.trendsChart.destroy();
        }
        // Maintenance Trends Chart
        const trendsCtx = document
          .getElementById("trendsChart")
          .getContext("2d");
        charts.trendsChart = new Chart(trendsCtx, {
          type: "line",
          data: {
            labels: [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "May",
              "Jun",
              "Jul",
              "Aug",
              "Sep",
              "Oct",
              "Nov",
              "Dec",
            ],
            datasets: [
              {
                label: "Preventive",
                data: [12, 15, 18, 14, 16, 20, 22, 19, 21, 23, 25, 27],
                borderColor: "#10b981",
                backgroundColor: "rgba(16, 185, 129, 0.1)",
                tension: 0.4,
              },
              {
                label: "Corrective",
                data: [8, 6, 7, 5, 4, 3, 5, 4, 3, 2, 4, 3],
                borderColor: "#3b82f6",
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                tension: 0.4,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: "top",
              },
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  drawBorder: false,
                },
              },
              x: {
                grid: {
                  display: false,
                },
              },
            },
          },
        });

        if (charts.healthChart) {
          charts.healthChart.destroy();
        }
        // Health Distribution Chart
        const healthCtx = document
          .getElementById("healthChart")
          .getContext("2d");
        charts.healthChart = new Chart(healthCtx, {
          type: "doughnut",
          data: {
            labels: ["Excellent", "Good", "Fair", "Poor"],
            datasets: [
              {
                data: [15, 8, 5, 2],
                backgroundColor: ["#10b981", "#3b82f6", "#f59e0b", "#ef4444"],
                borderWidth: 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "70%",
            plugins: {
              legend: {
                position: "bottom",
              },
            },
          },
        });

        if (charts.performanceChart) {
          charts.performanceChart.destroy();
        }
        // Team Performance Chart
        const performanceCtx = document
          .getElementById("performanceChart")
          .getContext("2d");
        charts.performanceChart = new Chart(performanceCtx, {
          type: "bar",
          data: {
            labels: teamsData.map((team) => team.name),
            datasets: [
              {
                label: "Completed This Month",
                data: teamsData.map((team) => team.completedThisMonth),
                backgroundColor: "#3b82f6",
                borderRadius: 6,
              },
              {
                label: "Active Requests",
                data: teamsData.map((team) => team.activeRequests),
                backgroundColor: "#f59e0b",
                borderRadius: 6,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: "top",
              },
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  drawBorder: false,
                },
              },
              x: {
                grid: {
                  display: false,
                },
              },
            },
          },
        });

        if (charts.costChart) {
          charts.costChart.destroy();
        }
        // Cost Analysis Chart
        const costCtx = document.getElementById("costChart").getContext("2d");
        charts.costChart = new Chart(costCtx, {
          type: "radar",
          data: {
            labels: ["Labor", "Parts", "Downtime", "Preventive", "Emergency"],
            datasets: [
              {
                label: "Cost Distribution",
                data: [45, 30, 15, 8, 2],
                backgroundColor: "rgba(59, 130, 246, 0.2)",
                borderColor: "#3b82f6",
                pointBackgroundColor: "#3b82f6",
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              r: {
                beginAtZero: true,
              },
            },
          },
        });
      }

      function updateAnalytics() {
        const period = document.getElementById("analyticsPeriod").value;
        showToast(`Updated analytics for ${period}`, "info");
        // In a real app, this would update chart data based on the selected period
      }

      function downloadChart(chartId) {
        const canvas = document.getElementById(chartId);
        const link = document.createElement("a");
        link.download = `${chartId}.png`;
        link.href = canvas.toDataURL("image/png");
        link.click();
        showToast("Chart downloaded successfully!", "success");
      }

      // ===== PREDICTIVE =====
      function loadPredictive() {
        loadPredictiveInsights();
      }

      function loadPredictiveInsights() {
        const insights = [
          {
            icon: "fas fa-robot",
            title: "Pattern Detected",
            description: "Equipment failures correlate with temperature spikes",
            action: "Monitor temperature thresholds",
          },
          {
            icon: "fas fa-lightbulb",
            title: "Optimization Opportunity",
            description: "Maintenance intervals can be extended by 15%",
            action: "Adjust maintenance schedule",
          },
          {
            icon: "fas fa-chart-bar",
            title: "Cost Saving Identified",
            description: "Predictive maintenance could reduce costs by 23%",
            action: "Implement AI recommendations",
          },
        ];

        const container = document.getElementById("predictiveInsights");
        container.innerHTML = "";

        insights.forEach((insight) => {
          const item = document.createElement("div");
          item.className = "recommendation-item";
          item.innerHTML = `
                      <div class="recommendation-icon">
                          <i class="${insight.icon}"></i>
                      </div>
                      <div class="recommendation-content">
                          <h4>${insight.title}</h4>
                          <p>${insight.description}</p>
                          <div class="recommendation-meta">
                              <span><i class="fas fa-bolt"></i> ${insight.action}</span>
                          </div>
                      </div>
                  `;
          container.appendChild(item);
        });
      }

      function runPredictiveAnalysis() {
        const btn =
          document.getElementById("aiInsightsBtn") ||
          document.querySelector('[onclick="runPredictiveAnalysis()"]');
        if (btn) btn.classList.add("loading");

        showToast("Running predictive analysis...", "info");

        setTimeout(() => {
          if (btn) btn.classList.remove("loading");
          loadPredictiveInsights();
          showToast("Predictive analysis completed!", "success");
        }, 2000);
      }

      // ===== REPORTS =====
      function loadReports() {
        // Initialize report charts
        const monthlyCtx = document
          .getElementById("monthlyChart")
          .getContext("2d");
        new Chart(monthlyCtx, {
          type: "bar",
          data: {
            labels: ["Q1", "Q2", "Q3", "Q4"],
            datasets: [
              {
                label: "Maintenance Hours",
                data: [320, 280, 350, 400],
                backgroundColor: "#3b82f6",
              },
            ],
          },
        });

        const costBreakdownCtx = document
          .getElementById("costBreakdownChart")
          .getContext("2d");
        new Chart(costBreakdownCtx, {
          type: "pie",
          data: {
            labels: ["Labor", "Parts", "Software", "Training"],
            datasets: [
              {
                data: [45, 30, 15, 10],
                backgroundColor: ["#3b82f6", "#10b981", "#f59e0b", "#8b5cf6"],
              },
            ],
          },
        });
      }

      function generateReport() {
        showToast("Generating PDF report...", "info");
        setTimeout(() => {
          showToast("Report generated successfully!", "success");
        }, 2000);
      }

      // ===== TEAMS =====
      function loadTeams() {
        const container = document.getElementById("teamsGrid");
        container.innerHTML = "";

        teamsData.forEach((team) => {
          const card = document.createElement("div");
          card.className = "equipment-card";
          card.innerHTML = `
                      <div class="equipment-header">
                          <div class="equipment-title">
                              <h3>${team.name}</h3>
                              <span>Team ID: TM-${team.id
                                .toString()
                                .padStart(3, "0")}</span>
                          </div>
                          <span class="equipment-status status-working">
                              Active
                          </span>
                      </div>

                      <div class="equipment-details">
                          <div class="detail-item">
                              <span class="detail-label">Members</span>
                              <span class="detail-value">${team.members}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Active Requests</span>
                              <span class="detail-value">${
                                team.activeRequests
                              }</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Completed (Month)</span>
                              <span class="detail-value">${
                                team.completedThisMonth
                              }</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Efficiency</span>
                              <span class="detail-value">${Math.round(
                                (team.completedThisMonth /
                                  (team.completedThisMonth +
                                    team.activeRequests)) *
                                  100
                              )}%</span>
                          </div>
                      </div>

                      <div class="equipment-actions">
                          <button class="btn btn-sm btn-secondary" onclick="viewTeam(${
                            team.id
                          })">
                              <i class="fas fa-eye"></i> View
                          </button>
                          <button class="btn btn-sm btn-primary" onclick="assignToTeam(${
                            team.id
                          })">
                              <i class="fas fa-user-plus"></i> Assign
                          </button>
                      </div>
                  `;
          container.appendChild(card);
        });
      }

      function viewTeam(id) {
        const team = teamsData.find((t) => t.id === id);
        if (!team) return;

        showToast(`Viewing ${team.name} team details`, "info");
      }

      function assignToTeam(id) {
        const team = teamsData.find((t) => t.id === id);
        if (!team) return;

        showToast(`Assigning task to ${team.name}`, "info");
      }

      function showAddTeamModal() {
        showToast("Add team feature coming soon!", "info");
      }

      // ===== SETTINGS =====
      function loadSettings() {
        // Settings are loaded from the HTML
      }

      function saveUserSettings() {
        showToast("User settings saved successfully!", "success");
      }

      function saveNotificationSettings() {
        showToast("Notification preferences saved!", "success");
      }

      // ===== CONTEXT MENU =====
      function showContextMenu(e, item) {
        e.preventDefault();
        currentContextTarget = item;

        const contextMenu = document.getElementById("contextMenu");
        contextMenu.style.top = e.pageY + "px";
        contextMenu.style.left = e.pageX + "px";
        contextMenu.classList.add("active");

        document.addEventListener("click", hideContextMenu);
      }

      function hideContextMenu() {
        document.getElementById("contextMenu").classList.remove("active");
        document.removeEventListener("click", hideContextMenu);
      }

      function editItem() {
        if (currentContextTarget) {
          showToast(`Editing ${currentContextTarget.name}`, "info");
        }
        hideContextMenu();
      }

      function deleteItem() {
        if (currentContextTarget) {
          if (
            confirm(
              `Are you sure you want to delete ${currentContextTarget.name}?`
            )
          ) {
            showToast(
              `${currentContextTarget.name} deleted successfully`,
              "success"
            );
          }
        }
        hideContextMenu();
      }

      function scheduleItem() {
        if (currentContextTarget) {
          showScheduleModal();
        }
        hideContextMenu();
      }

      function assignItem() {
        if (currentContextTarget) {
          showToast(`Assigning ${currentContextTarget.name} to team`, "info");
        }
        hideContextMenu();
      }

      // ===== HEADER FUNCTIONS =====
      function showNotifications() {
        showToast("You have 3 new notifications", "info");
      }

      function showHelp() {
        showToast("Opening help center...", "info");
      }

      function toggleFullscreen() {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen().catch((err) => {
            console.log(
              `Error attempting to enable fullscreen: ${err.message}`
            );
          });
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          }
        }
      }

      function toggleUserMenu() {
        showToast("User menu clicked", "info");
      }

      // ===== THEME MANAGEMENT =====
      function initTheme() {
        const themeToggle = document.getElementById("themeToggle");
        const prefersDark = window.matchMedia(
          "(prefers-color-scheme: dark)"
        ).matches;
        const storedTheme =
          localStorage.getItem("theme") || (prefersDark ? "dark" : "light");

        if (storedTheme === "dark") {
          document.documentElement.setAttribute("data-theme", "dark");
          document.body.classList.add("dark-mode");
          themeToggle.checked = true;
        }

        themeToggle.addEventListener("change", function () {
          if (this.checked) {
            document.documentElement.setAttribute("data-theme", "dark");
            document.body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark");
          } else {
            document.documentElement.removeAttribute("data-theme");
            document.body.classList.remove("dark-mode");
            localStorage.setItem("theme", "light");
          }
        });
      }

      /* ===== INITIALIZATION ===== */

      // App Entry Point
      document.addEventListener("DOMContentLoaded", () => {
        initTheme();

        // Initialize modules
        if (typeof loadTeams === "function") loadTeams();
        if (typeof loadReports === "function") loadReports();
        if (typeof loadAnalytics === "function") loadAnalytics();
        if (typeof loadPredictive === "function") loadPredictive();
        if (typeof loadUpcomingEvents === "function") loadUpcomingEvents();
        if (typeof setupCalendarDragDrop === "function")
          setupCalendarDragDrop();
        if (typeof renderCalendar === "function") renderCalendar();

        // Navigate to Dashboard
        if (typeof navigateTo === "function") {
          navigateTo("dashboard");
        } else {
          // Fallback: Manually show dashboard
          const dashboard = document.getElementById("dashboard");
          if (dashboard) dashboard.classList.add("active");

          // Define navigateTo if missing for internal calls
          window.navigateTo = function (pageId) {
            document
              .querySelectorAll(".page-content")
              .forEach((p) => p.classList.remove("active"));
            const page = document.getElementById(pageId);
            if (page) page.classList.add("active");

            document.querySelectorAll(".nav-item").forEach((n) => {
              n.classList.remove("active");
              if (
                n.querySelector("a") &&
                n.querySelector("a").getAttribute("onclick") &&
                n.querySelector("a").getAttribute("onclick").includes(pageId)
              ) {
                n.classList.add("active");
              }
            });
          };
        }
      });
      fetch("api/get-dashboard.php")
  .then(res => res.json())
  .then(data => {
    document.querySelector(".stat-value").innerText =
      data.active_equipment + "/" + data.total_equipment;
  });
