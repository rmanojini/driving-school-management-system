<?php
// includes/rate_limiter.php

/**
 * Check if the rate limit has been exceeded for a given action and IP.
 * 
 * @param mysqli $con Database connection
 * @param string $ip User's IP address
 * @param string $action Action type (e.g., 'login', 'register')
 * @param int $limit Max attempts allowed
 * @param int $seconds Time window in seconds
 * @return bool True if limit exceeded, False otherwise
 */
function is_rate_limited($con, $ip, $action, $limit, $seconds) {
    // 1. Clean up old records (Optional garbage collection, minimal performance hit)
    // mysqli_query($con, "DELETE FROM rate_limits WHERE attempt_time < NOW() - INTERVAL 1 DAY");

    // 2. Count attempts within the window
    $sql = "SELECT COUNT(*) as count FROM rate_limits 
            WHERE ip_address = ? AND action_type = ? 
            AND attempt_time > (NOW() - INTERVAL ? SECOND)";
            
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $ip, $action, $seconds);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return ($row['count'] >= $limit);
}

/**
 * Log a new attempt.
 */
function log_rate_limit($con, $ip, $action) {
    $sql = "INSERT INTO rate_limits (ip_address, action_type, attempt_time) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $ip, $action);
    mysqli_stmt_execute($stmt);
}
?>
