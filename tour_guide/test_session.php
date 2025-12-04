<?php
// test_session.php
session_start();

echo "<h2>Session Test</h2>";
echo "<pre>";
echo "Session Status: " . session_status() . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Name: " . session_name() . "\n";
echo "\nSession Data:\n";
print_r($_SESSION);

// Kiểm tra cụ thể
echo "\n=== Specific Checks ===\n";
echo "guide_id: " . ($_SESSION['guide_id'] ?? 'NOT SET') . "\n";
echo "guide_name: " . ($_SESSION['guide_name'] ?? 'NOT SET') . "\n";
echo "logged_in: " . ($_SESSION['logged_in'] ?? 'NOT SET') . "\n";

// Test set session
if (isset($_GET['set_id'])) {
    $_SESSION['guide_id'] = $_GET['set_id'];
    $_SESSION['guide_name'] = 'Test User ' . $_GET['set_id'];
    $_SESSION['logged_in'] = true;
    echo "\n=== Set session to ID " . $_GET['set_id'] . " ===\n";
    echo "Refresh page to see changes.\n";
}

// Clear session
if (isset($_GET['clear'])) {
    session_destroy();
    session_start();
    echo "\n=== Session Cleared ===\n";
}
echo "</pre>";

echo "<p><a href='test_session.php'>Reload</a></p>";
echo "<p><a href='test_session.php?clear=1'>Clear Session</a></p>";
echo "<p><a href='test_session.php?set_id=8'>Set ID to 8</a></p>";
echo "<p><a href='test_session.php?set_id=6'>Set ID to 6</a></p>";
?>