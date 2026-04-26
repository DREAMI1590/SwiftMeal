<?php
// Sudah ada parse_price, tambahkan currency_format juga
function currency_format($value) {
    try {
        return "Rp " . number_format($value, 0, ',', '.') . ",00";
    } catch (Exception $e) {
        return $value;
    }
}
?>
