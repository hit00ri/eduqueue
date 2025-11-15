(function () {
    const path = location.pathname;

    const refreshPages = [
        "dashboard.php",
        "student_dashboard.php",
        "reports.php"
    ];

    if (refreshPages.some(page => path.endsWith(page))) {
        setTimeout(() => location.reload(), 120000); // 2 minutes
    }
})();
