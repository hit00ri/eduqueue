<?php
require_once __DIR__ . "/../db/config.php";
require_once __DIR__ . "/services/MetricsService.php";

$metricsService = new MetricsService($conn);

// Helper: check if a table exists
function tableExists($conn, $table) {
	$stmt = $conn->prepare("SHOW TABLES LIKE ?");
	$stmt->execute([$table]);
	return $stmt->fetch() !== false;
}

// Today's basic counts
$todayTotal = 0;
$todayServed = 0;
$todayVoid = 0;
$todayEfficiency = 0;
$avgWaitTime = 0; // in minutes
$avgServiceTime = 0; // in minutes
$todayRevenue = 0.00;

try {
	$row = $conn->query("SELECT
		COUNT(*) as total_queues,
		SUM(CASE WHEN status = 'served' THEN 1 ELSE 0 END) as today_served,
		SUM(CASE WHEN status = 'voided' THEN 1 ELSE 0 END) as today_void
		FROM queue WHERE DATE(time_in) = CURDATE()")
		->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$todayTotal = intval($row['total_queues']);
		$todayServed = intval($row['today_served']);
		$todayVoid = intval($row['today_void']);
		$todayEfficiency = $todayTotal > 0 ? round(($todayServed / $todayTotal) * 100, 1) : 0;
	}

	// Revenue today
	$rev = $conn->query("SELECT IFNULL(SUM(amount),0) as total_rev FROM transactions WHERE DATE(date_paid)=CURDATE() AND status='completed'")->fetch(PDO::FETCH_ASSOC);
	$todayRevenue = $rev ? floatval($rev['total_rev']) : 0.00;

	// Avg wait/service times: prefer system_metrics if available
	if (tableExists($conn, 'system_metrics')) {
		$times = $conn->query("SELECT
			AVG(wait_time_seconds) as avg_wait,
			AVG(service_time_seconds) as avg_service
			FROM system_metrics sm
			JOIN queue q ON sm.queue_id = q.queue_id
			WHERE DATE(q.time_in) = CURDATE()")
			->fetch(PDO::FETCH_ASSOC);

		if ($times) {
			$avgWaitTime = $times['avg_wait'] !== null ? round($times['avg_wait'] / 60, 1) : 0;
			$avgServiceTime = $times['avg_service'] !== null ? round($times['avg_service'] / 60, 1) : 0;
		}
	} else {
		// Fallback: try to compute using time_in/time_out if time_out is DATETIME
		$times = $conn->query("SELECT
			AVG(TIMESTAMPDIFF(SECOND, q.time_in, FROM_UNIXTIME(q.time_out))) as avg_wait_seconds
			FROM queue q
			WHERE DATE(q.time_in) = CURDATE() AND q.time_out IS NOT NULL AND q.time_out > 0")
			->fetch(PDO::FETCH_ASSOC);

		if ($times && $times['avg_wait_seconds'] !== null) {
			$avgWaitTime = round($times['avg_wait_seconds'] / 60, 1);
		}
	}

} catch (Exception $e) {
	// keep defaults on error
}

// Weekly metrics (last 7 days)
$weeklyMetrics = [];
try {
	$weeklySql = "SELECT
		DATE(q.time_in) as summary_date,
		COUNT(*) as total_queues,
		SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) as served_count,
		ROUND((SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as service_efficiency_rate,
		AVG(sm.wait_time_seconds) as avg_wait_time,
		AVG(sm.service_time_seconds) as avg_service_time,
		IFNULL(SUM(t.amount), 0) as total_transaction_volume
		FROM queue q
		LEFT JOIN transactions t ON q.queue_id = t.queue_id AND t.status = 'completed'
		LEFT JOIN system_metrics sm ON q.queue_id = sm.queue_id
		WHERE q.time_in >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
		GROUP BY DATE(q.time_in)
		ORDER BY DATE(q.time_in) DESC";

	$weeklyMetrics = $conn->query($weeklySql)->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
	$weeklyMetrics = [];
}

// Cashier performance (use MetricsService if available)
$cashierPerformance = [];
try {
	if (method_exists($metricsService, 'getAllCashiersPerformance')) {
		$cashierPerformance = $metricsService->getAllCashiersPerformance(7);
	}
} catch (Exception $e) {
	$cashierPerformance = [];
}

// Recent logs
$recentLogs = [];
try {
	if (tableExists($conn, 'metrics_log')) {
		$recentLogs = $conn->query("SELECT created_at, message FROM metrics_log ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
	}
} catch (Exception $e) {
	$recentLogs = [];
}

// Ensure variables exist for the template
$todayEfficiency = $todayEfficiency ?? 0;
$avgWaitTime = $avgWaitTime ?? 0;
$avgServiceTime = $avgServiceTime ?? 0;
$todayRevenue = $todayRevenue ?? 0.00;
$weeklyMetrics = is_array($weeklyMetrics) ? $weeklyMetrics : [];
$cashierPerformance = is_array($cashierPerformance) ? $cashierPerformance : [];
$recentLogs = is_array($recentLogs) ? $recentLogs : [];

?>
