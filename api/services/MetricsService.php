<?php
require_once "db/config.php";

class MetricsService {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // ... your existing methods (recordQueueCompletion, generateDailyKPISummary, etc.) ...
    
    /**
     * Get cashier-specific performance metrics
     * Requires 'handled_by' column in queue table
     */
    public function getCashierMetrics($cashier_id, $days = 7) {
        $stmt = $this->conn->prepare("
            SELECT 
                DATE(q.time_in) as service_date,
                COUNT(*) as total_queues,
                SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) as served_count,
                SUM(CASE WHEN q.status = 'voided' THEN 1 ELSE 0 END) as voided_count,
                AVG(sm.wait_time_seconds) as avg_wait_time,
                AVG(sm.service_time_seconds) as avg_service_time,
                SUM(t.amount) as total_revenue,
                COUNT(t.transaction_id) as transaction_count,
                ROUND(
                    (SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 
                    2
                ) as service_efficiency_rate
                
            FROM queue q
            LEFT JOIN system_metrics sm ON q.queue_id = sm.queue_id
            LEFT JOIN transactions t ON q.queue_id = t.queue_id AND t.status = 'completed'
            
            WHERE q.handled_by = ? 
            AND q.time_in >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            
            GROUP BY DATE(q.time_in)
            ORDER BY service_date DESC
        ");
        
        $stmt->execute([$cashier_id, $days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get individual cashier performance summary
     */
    public function getCashierPerformanceSummary($cashier_id, $days = 30) {
        $stmt = $this->conn->prepare("
            SELECT 
                u.name as cashier_name,
                COUNT(DISTINCT q.queue_id) as total_queues_handled,
                SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) as served_count,
                SUM(CASE WHEN q.status = 'voided' THEN 1 ELSE 0 END) as voided_count,
                AVG(sm.wait_time_seconds) as avg_wait_time,
                AVG(sm.service_time_seconds) as avg_service_time,
                SUM(t.amount) as total_revenue_generated,
                COUNT(DISTINCT t.transaction_id) as transactions_processed,
                ROUND(
                    (SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) / COUNT(DISTINCT q.queue_id)) * 100, 
                    2
                ) as overall_efficiency_rate
                
            FROM users u
            LEFT JOIN queue q ON u.user_id = q.handled_by
            LEFT JOIN system_metrics sm ON q.queue_id = sm.queue_id
            LEFT JOIN transactions t ON q.queue_id = t.queue_id AND t.status = 'completed'
            
            WHERE u.user_id = ? 
            AND u.role = 'cashier'
            AND q.time_in >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            
            GROUP BY u.user_id, u.name
        ");
        
        $stmt->execute([$cashier_id, $days]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Compare all cashiers performance (Admin only)
     */
    public function getAllCashiersPerformance($days = 30) {
        $stmt = $this->conn->prepare("
            SELECT 
                u.user_id,
                u.name as cashier_name,
                COUNT(DISTINCT q.queue_id) as total_queues_handled,
                SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) as served_count,
                SUM(CASE WHEN q.status = 'voided' THEN 1 ELSE 0 END) as voided_count,
                AVG(sm.wait_time_seconds) as avg_wait_time,
                AVG(sm.service_time_seconds) as avg_service_time,
                SUM(t.amount) as total_revenue_generated,
                COUNT(DISTINCT t.transaction_id) as transactions_processed,
                ROUND(
                    (SUM(CASE WHEN q.status = 'served' THEN 1 ELSE 0 END) / COUNT(DISTINCT q.queue_id)) * 100, 
                    2
                ) as overall_efficiency_rate,
                
                -- Today's performance
                SUM(CASE WHEN DATE(q.time_in) = CURDATE() THEN 1 ELSE 0 END) as today_queues,
                SUM(CASE WHEN DATE(q.time_in) = CURDATE() AND q.status = 'served' THEN 1 ELSE 0 END) as today_served
                
            FROM users u
            LEFT JOIN queue q ON u.user_id = q.handled_by
            LEFT JOIN system_metrics sm ON q.queue_id = sm.queue_id
            LEFT JOIN transactions t ON q.queue_id = t.queue_id AND t.status = 'completed'
            
            WHERE u.role = 'cashier'
            AND q.time_in >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            
            GROUP BY u.user_id, u.name
            ORDER BY overall_efficiency_rate DESC, total_revenue_generated DESC
        ");
        
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Log a generic event to metrics_log (creates table if missing)
     */
    public function logEvent($level, $category, $message, $user_id = null, $student_id = null, $queue_id = null) {
        // Ensure table exists
        $this->conn->exec(
            "CREATE TABLE IF NOT EXISTS metrics_log (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                level VARCHAR(20),
                category VARCHAR(50),
                message TEXT,
                user_id INT NULL,
                student_id INT NULL,
                queue_id INT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $stmt = $this->conn->prepare("INSERT INTO metrics_log (level, category, message, user_id, student_id, queue_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$level, $category, $message, $user_id, $student_id, $queue_id]);
        return $this->conn->lastInsertId();
    }

    /**
     * Record queue completion metrics (served/voided). Creates table if missing.
     */
    public function recordQueueCompletion($queue_id, $student_id, $status) {
        $allowed = ['served', 'voided'];
        if (!in_array($status, $allowed)) {
            $status = 'served';
        }

        $this->conn->exec(
            "CREATE TABLE IF NOT EXISTS queue_metrics (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                queue_id INT NOT NULL,
                student_id INT NULL,
                status VARCHAR(20),
                recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $stmt = $this->conn->prepare("INSERT INTO queue_metrics (queue_id, student_id, status) VALUES (?, ?, ?)");
        $stmt->execute([$queue_id, $student_id, $status]);
        return $this->conn->lastInsertId();
    }

    /**
     * Generate and store the daily KPI summary for a given date (YYYY-MM-DD)
     */
    public function generateDailyKPISummary($date) {
        // Ensure summary table exists
        $this->conn->exec(
            "CREATE TABLE IF NOT EXISTS daily_kpi_summary (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                summary_date DATE NOT NULL UNIQUE,
                total_queues INT DEFAULT 0,
                served_count INT DEFAULT 0,
                voided_count INT DEFAULT 0,
                avg_wait_seconds DOUBLE DEFAULT NULL,
                avg_service_seconds DOUBLE DEFAULT NULL,
                total_revenue DECIMAL(12,2) DEFAULT 0.00,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        // Compute KPIs
        $stmt = $this->conn->prepare("SELECT
            COUNT(*) as total_queues,
            SUM(CASE WHEN status = 'served' THEN 1 ELSE 0 END) as served_count,
            SUM(CASE WHEN status = 'voided' THEN 1 ELSE 0 END) as voided_count
            FROM queue WHERE DATE(time_in) = ?");
        $stmt->execute([$date]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = intval($row['total_queues'] ?? 0);
        $served = intval($row['served_count'] ?? 0);
        $voided = intval($row['voided_count'] ?? 0);

        // Avg times from system_metrics if available
        $avgWait = null;
        $avgService = null;
        $r = $this->conn->prepare("SELECT AVG(wait_time_seconds) as aw, AVG(service_time_seconds) as asv
            FROM system_metrics sm
            JOIN queue q ON sm.queue_id = q.queue_id
            WHERE DATE(q.time_in) = ?");
        try {
            $r->execute([$date]);
            $t = $r->fetch(PDO::FETCH_ASSOC);
            if ($t) {
                $avgWait = $t['aw'] !== null ? floatval($t['aw']) : null;
                $avgService = $t['asv'] !== null ? floatval($t['asv']) : null;
            }
        } catch (Exception $e) {
            // ignore, leave nulls
        }

        // Revenue
        $rv = $this->conn->prepare("SELECT IFNULL(SUM(amount),0) as total_rev FROM transactions WHERE DATE(date_paid)=? AND status='completed'");
        $rv->execute([$date]);
        $revRow = $rv->fetch(PDO::FETCH_ASSOC);
        $totalRevenue = $revRow ? floatval($revRow['total_rev']) : 0.00;

        // Insert or update summary
        $up = $this->conn->prepare("INSERT INTO daily_kpi_summary (summary_date, total_queues, served_count, voided_count, avg_wait_seconds, avg_service_seconds, total_revenue)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            total_queues = VALUES(total_queues),
            served_count = VALUES(served_count),
            voided_count = VALUES(voided_count),
            avg_wait_seconds = VALUES(avg_wait_seconds),
            avg_service_seconds = VALUES(avg_service_seconds),
            total_revenue = VALUES(total_revenue)
        ");

        $up->execute([
            $date,
            $total,
            $served,
            $voided,
            $avgWait,
            $avgService,
            $totalRevenue
        ]);

        return true;
    }

    // ... rest of your existing methods ...
}
?>